<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function pathao(Request $request, OrderService $orderService)
    {
        $secret = Setting::get('pathao_webhook_secret');

        // Handshake / verification ping — Pathao expects 202 + secret header back
        if (!$request->has('order_status')) {
            return response()->json(['message' => 'OK'], 202)
                ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $secret ?? '');
        }

        // Live event — log and process. No HMAC check; Pathao does not sign these.
        Log::info('Pathao webhook payload', $request->all());

        $consignmentId = $request->input('consignment_id');
        $pathaoStatus = $request->input('order_status');

        if (!$consignmentId || !$pathaoStatus) {
            Log::warning('Pathao webhook missing fields', $request->all());
            return response()->json(['message' => 'Missing fields'], 202)
                ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $secret ?? '');
        }

        $order = Order::where('pathao_consignment_id', $consignmentId)->first();

        if (!$order) {
            Log::warning("Pathao webhook: no order found for consignment {$consignmentId}");
            return response()->json(['message' => 'Order not found'], 202)
                ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $secret ?? '');
        }

        if ($pathaoStatus !== $order->pathao_status) {
            $order->update(['pathao_status' => $pathaoStatus]);

            $statusMap = [
                'Pickup_Completed' => 'shipped',
                'Delivery_Completed' => 'delivered',
                'Delivery_Cancelled' => 'cancelled',
                'Return_Completed' => 'returned',
            ];

            if (!empty($statusMap[$pathaoStatus])) {
                $orderService->updateStatus($order, $statusMap[$pathaoStatus], 'Auto-synced from Pathao', false);
            }

            Log::info("Pathao webhook: order #{$order->order_number} updated to {$pathaoStatus}");
        }

        return response()->json(['message' => 'OK'], 202)
            ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $secret ?? '');
    }

    public function steadfast(Request $request, OrderService $orderService)
    {
        Log::info('Steadfast webhook', $request->all());

        // Verify Steadfast Bearer token
        $expectedToken = Setting::get('steadfast_bearer_token');
        $incoming = $request->bearerToken(); // reads Authorization: Bearer <token>

        if ($expectedToken && $incoming !== $expectedToken) {
            Log::warning('Steadfast webhook auth token mismatch');
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $invoice = $request->input('invoice');       // your order_number
        $sfStatus = $request->input('delivery_status');
        $consignmentId = $request->input('consignment_id');

        if (!$sfStatus || (!$invoice && !$consignmentId)) {
            return response()->json(['message' => 'Missing fields'], 400);
        }

        $order = $invoice
            ? Order::where('order_number', $invoice)->first()
            : Order::where('steadfast_consignment_id', $consignmentId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($sfStatus === $order->steadfast_status) {
            return response()->json(['message' => 'No change']);
        }

        $order->update(['steadfast_status' => $sfStatus]);

        $statusMap = [
            'delivered' => 'delivered',
            'partial_delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'hold' => 'on_hold',
            'delivered_approval_pending' => 'delivered',
            'partial_delivered_approval_pending' => 'delivered',
            'cancelled_approval_pending' => 'cancelled',
            'unknown' => null,
            'in_review' => null,
        ];

        $mapped = $statusMap[$sfStatus] ?? null;
        if ($mapped) {
            $orderService->updateStatus($order, $mapped, 'Auto-synced from Steadfast', false);
        }

        return response()->json(['message' => 'OK']);
    }
}