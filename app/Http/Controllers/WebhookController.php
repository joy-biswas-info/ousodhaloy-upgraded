<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function pathao(Request $request, OrderService $orderService, string $secret)
    {
        $expected = Setting::get('pathao_webhook_secret');

        // Pathao doesn't sign requests, so the shared secret rides in the
        // URL path instead (see routes/web.php). 404 rather than 401 on
        // mismatch — don't confirm to a prober that this endpoint exists.
        if (!$expected || !hash_equals($expected, $secret)) {
            abort(404);
        }

        // Handshake / verification ping — Pathao expects 202 + secret header back
        if (!$request->has('order_status')) {
            return response()->json(['message' => 'OK'], 202)
                ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $expected);
        }

        // Live event — log and process.
        Log::info('Pathao webhook payload', $request->all());

        // Pathao's own webhook docs list X-PATHAO-Signature ("secret provided
        // by you during integration") as a request header on real event
        // deliveries — on top of, not instead of, the URL-path secret above
        // (which is what their dashboard's own test/verification tool checks
        // via the response header). Defense in depth: even if the URL secret
        // ever leaked, a forged request would still need this header right.
        // Soft-fail (log only) rather than reject outright when it's simply
        // absent — we don't have a confirmed real payload showing Pathao
        // sends it on every single event type, and the URL secret alone is
        // already the primary gate.
        $signature = $request->header('X-PATHAO-Signature');
        if ($signature !== null && !hash_equals($expected, $signature)) {
            Log::warning('Pathao webhook: X-PATHAO-Signature present but did not match — rejecting', [
                'consignment_id' => $request->input('consignment_id'),
            ]);
            abort(404);
        } elseif ($signature === null) {
            Log::warning('Pathao webhook: X-PATHAO-Signature header missing from request — relying on URL secret only');
        }

        $consignmentId = $request->input('consignment_id');
        $pathaoStatus = $request->input('order_status');

        if (!$consignmentId || !$pathaoStatus) {
            Log::warning('Pathao webhook missing fields', $request->all());
            return response()->json(['message' => 'Missing fields'], 202)
                ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $expected);
        }

        $order = Order::where('pathao_consignment_id', $consignmentId)->first();

        if (!$order) {
            Log::warning("Pathao webhook: no order found for consignment {$consignmentId}");
            return response()->json(['message' => 'Order not found'], 202)
                ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $expected);
        }

        if ($pathaoStatus !== $order->pathao_status) {
            $order->update(['pathao_status' => $pathaoStatus]);

            $mapped = Order::mapCourierStatus('pathao', $pathaoStatus);
            if ($mapped) {
                $orderService->updateStatus($order, $mapped, 'Auto-synced from Pathao', false);
            } else {
                // Surfaces gaps in Order::mapCourierStatus()'s pathao map —
                // built from Pathao's dashboard documentation, not a captured
                // real payload for every status, so an unrecognized raw value
                // here means the map needs a new entry, not that anything's
                // broken. pathao_status above is still updated regardless.
                Log::warning("Pathao webhook: no internal status mapping for raw status \"{$pathaoStatus}\" (order #{$order->order_number})");
            }

            Log::info("Pathao webhook: order #{$order->order_number} updated to {$pathaoStatus}");
        }

        return response()->json(['message' => 'OK'], 202)
            ->header('X-Pathao-Merchant-Webhook-Integration-Secret', $expected);
    }

    public function steadfast(Request $request, OrderService $orderService)
    {
        Log::info('Steadfast webhook', $request->all());

        // Verify Steadfast Bearer token — fail CLOSED. An unconfigured
        // token must reject, not skip the check (that was the bug: an
        // empty $expectedToken used to make the whole check a no-op).
        $expectedToken = Setting::get('steadfast_bearer_token');
        $incoming = $request->bearerToken(); // reads Authorization: Bearer <token>

        if (!$expectedToken || !hash_equals($expectedToken, (string) $incoming)) {
            Log::warning('Steadfast webhook auth token mismatch or unconfigured');
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

        $mapped = Order::mapCourierStatus('steadfast', $sfStatus);
        if ($mapped) {
            $orderService->updateStatus($order, $mapped, 'Auto-synced from Steadfast', false);
        }

        return response()->json(['message' => 'OK']);
    }
}