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
        // ── Step 1: Pathao webhook verification handshake ──────────────
        // Pathao sends a verification request first and expects:
        // - HTTP 202
        // - Header: X-Pathao-Merchant-Webhook-Integration-Secret: <your-secret>
        $secret = \App\Models\Setting::get('pathao_webhook_secret');

        if ($request->has('challenge') || !$request->has('order_status')) {
            return response()->json(['message' => 'Verified'], 202)
                ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $secret ?? '');
        }

        // ── Step 2: Verify live webhook requests ───────────────────────
        $incoming = $request->header('X-Pathao-Signature')
            ?? $request->header('X-Hub-Signature-256');

        if ($secret && $incoming) {
            $expected = 'sha256=' . hash_hmac('sha256', $request->getContent(), $secret);
            if (!hash_equals($expected, $incoming)) {
                Log::warning('Pathao webhook signature mismatch');
                return response()->json(['message' => 'Invalid signature'], 401);
            }
        }

        // ── Step 3: Process status update ─────────────────────────────
        Log::info('Pathao webhook', $request->all());

        $consignmentId = $request->input('consignment_id');
        $pathaoStatus = $request->input('order_status');

        if (!$consignmentId || !$pathaoStatus) {
            return response()->json(['message' => 'Missing fields'], 400);
        }

        $order = Order::where('pathao_consignment_id', $consignmentId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($pathaoStatus === $order->pathao_status) {
            return response()->json(['message' => 'No change'], 202);
        }

        $order->update(['pathao_status' => $pathaoStatus]);

        $statusMap = [
            'Pickup_Requested' => null,
            'Pickup_Completed' => 'shipped',
            'Pickup_Failed' => null,
            'Delivery_Completed' => 'delivered',
            'Delivery_Failed' => null,
            'Delivery_Cancelled' => 'cancelled',
            'Return_Completed' => 'returned',
        ];

        if (isset($statusMap[$pathaoStatus]) && $statusMap[$pathaoStatus]) {
            $orderService->updateStatus($order, $statusMap[$pathaoStatus], 'Auto-synced from Pathao', false);
        }

        return response()->json(['message' => 'OK'], 202);
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