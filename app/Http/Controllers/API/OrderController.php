<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\PathaoService;
use App\Services\SteadfastService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PathaoService $pathao,
        private SteadfastService $steadfast,
    ) {
    }

    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user'])->latest();

        if ($request->filled('status'))
            $query->where('status', $request->status);

        if ($request->filled('payment_method'))
            $query->where('payment_method', $request->payment_method);

        if ($request->filled('courier'))
            $query->where('courier', $request->courier);

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('order_number', 'like', "%$q%")
                    ->orWhere('shipping_name', 'like', "%$q%")
                    ->orWhere('shipping_phone', 'like', "%$q%");
            });
        }

        if ($request->filled('date'))
            $query->whereDate('created_at', $request->date);

        $orders = $query->paginate(20);

        return response()->json([
            'data' => $orders->map(fn($o) => $this->formatOrder($o)),
            'total' => $orders->total(),
            'last_page' => $orders->lastPage(),
            'current_page' => $orders->currentPage(),
        ]);
    }

    // Manager-only namespace (see routes/api.php) — any authenticated
    // manager/admin can view any order, no ownership check needed. This
    // used to return a Blade view with an ownership check copy-pasted from
    // the customer-facing controller, which made every guest order
    // (no user_id) unreachable via this endpoint.
    public function show(Order $order)
    {
        $order->load(['items.product', 'statusHistory']);
        return response()->json(['order' => $this->formatOrderFull($order)]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Order::STATUS_LABELS)),
            'note' => 'nullable|string|max:500',
            'notify_customer' => 'nullable|boolean',
            'confirm_courier_cancel' => 'nullable|boolean',
        ]);

        // Same admin-editable gate as the web admin's updateStatus() — without
        // it, a client could set ready_to_ship/shipped/out_for_delivery/
        // delivered/returned directly, statuses meant to be set only by the
        // push-to-courier actions and the courier webhook. canTransitionTo()
        // alone would allow it (it has to, for those other callers).
        if (!in_array($request->status, $order->adminSelectableStatuses(), true)) {
            $reason = $request->status === 'cancelled' && !$order->canCancel()
                ? 'This order can no longer be cancelled — the package has already been picked up by the courier.'
                : "\"" . (Order::STATUS_LABELS[$request->status] ?? $request->status) . '" is set automatically by the courier and can\'t be chosen manually.';
            return response()->json(['message' => $reason], 422);
        }

        // Same courier-cancel gate as the web admin — neither courier API
        // supports remote cancellation, so cancelling a courier-linked
        // order here only changes our own status.
        $consignmentId = $order->pathao_consignment_id ?: $order->steadfast_consignment_id;
        $courierName = $order->pathao_consignment_id ? 'Pathao' : ($order->steadfast_consignment_id ? 'Steadfast' : null);
        if ($request->status === 'cancelled' && $consignmentId && !$request->boolean('confirm_courier_cancel')) {
            return response()->json([
                'message' => "This order was already pushed to {$courierName} (consignment {$consignmentId}). Cancelling here only updates the order status — you must also cancel it manually in {$courierName}'s merchant dashboard.",
                'requires_confirmation' => true,
                'courier' => $courierName,
                'consignment_id' => $consignmentId,
            ], 409);
        }

        // Delegate to the same service every other order-status path uses
        // (web admin, bulk actions, webhooks) — stock restore, held_from_status
        // tracking, and the transition guard all apply identically here.
        $updated = $this->orderService->updateStatus(
            $order,
            $request->status,
            $request->note ?? '',
            $request->boolean('notify_customer', true)
        );

        if (!$updated) {
            return response()->json([
                'message' => "Can't move this order from \"{$order->status_label}\" to \"" . (Order::STATUS_LABELS[$request->status] ?? $request->status) . '".',
            ], 422);
        }

        return response()->json(['message' => 'Status updated', 'status' => $request->status]);
    }

    public function addNote(Request $request, Order $order)
    {
        $request->validate(['note' => 'required|string|max:1000']);
        $order->update(['admin_note' => $request->note]);
        return response()->json(['message' => 'Note saved']);
    }

    public function pushToPathao(Order $order)
    {
        if ($order->status !== 'processing') {
            return response()->json([
                'message' => "Order must be in \"Processing\" status before pushing to a courier — currently \"{$order->status_label}\".",
            ], 422);
        }

        try {
            $result = $this->pathao->createOrder($order);
            if (!$result['success']) {
                return response()->json(['message' => $result['error'] ?? 'Failed to push to Pathao.'], 422);
            }

            $this->orderService->updateStatus($order, 'ready_to_ship', 'Pushed to Pathao', false);

            return response()->json([
                'message' => 'Pushed to Pathao',
                'order' => $this->formatOrderFull($order->fresh(['items.product', 'statusHistory'])),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function pushToSteadfast(Order $order)
    {
        if ($order->status !== 'processing') {
            return response()->json([
                'message' => "Order must be in \"Processing\" status before pushing to a courier — currently \"{$order->status_label}\".",
            ], 422);
        }

        try {
            $result = $this->steadfast->createOrder($order);
            if (!$result['success']) {
                return response()->json(['message' => $result['error'] ?? 'Failed to push to Steadfast.'], 422);
            }

            $this->orderService->updateStatus($order, 'ready_to_ship', 'Pushed to Steadfast', false);

            return response()->json([
                'message' => 'Pushed to Steadfast',
                'order' => $this->formatOrderFull($order->fresh(['items.product', 'statusHistory'])),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // Reuses the real syncOrderStatus() on each service (already wired to
    // Order::mapCourierStatus() -> OrderService::updateStatus()) instead of
    // reimplementing the courier-status mapping inline — that version also
    // called a method that doesn't exist on PathaoService.
    public function syncCourier(Order $order)
    {
        try {
            if ($order->courier === 'steadfast' && $order->steadfast_consignment_id) {
                $this->steadfast->syncOrderStatus($order);
            } elseif ($order->courier === 'pathao' && $order->pathao_consignment_id) {
                $this->pathao->syncOrderStatus($order);
            } else {
                return response()->json(['message' => 'This order has no courier consignment to sync.'], 422);
            }

            return response()->json([
                'message' => 'Synced',
                'order' => $this->formatOrderFull($order->fresh(['items.product', 'statusHistory'])),
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    // Admin-only — same reasoning as the web route (see routes/web.php's
    // admin-only staff-management group): undoing a cancel/return
    // re-deducts stock, not something a manager-level mistake should be
    // able to trigger alone.
    public function reopen(Request $request, Order $order)
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden — admin only.'], 403);
        }

        $request->validate(['note' => 'nullable|string|max:500']);

        $result = $this->orderService->reopenOrder($order, $request->note ?? '');

        if (!$result['success']) {
            return response()->json(['message' => $result['error']], 422);
        }

        return response()->json([
            'message' => 'Order reopened — moved back to Processing.',
            'order' => $this->formatOrderFull($order->fresh(['items.product', 'statusHistory'])),
        ]);
    }

    private function formatOrder(Order $o): array
    {
        return [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'customer_name' => $o->customer_name,
            'customer_phone' => $o->customer_phone,
            'status' => $o->status,
            'payment_method' => $o->payment_method,
            'payment_status' => $o->payment_status,
            'courier' => $o->courier,
            'total' => (float) $o->total,
            'items_count' => $o->items->count(),
            'district' => $o->shipping_district,
            'created_at' => $o->created_at->toIso8601String(),
            // adminSelectableStatuses(), not the raw STATUS_FLOW map — the
            // latter is the full state machine including courier-driven
            // targets (ready_to_ship/shipped/out_for_delivery/delivered/
            // returned) that only the push-to-courier action or a webhook
            // may set. The web admin already restricts its dropdown to this
            // same subset; this endpoint listing the wider set would let the
            // app build a status picker that can attempt transitions
            // updateStatus() itself doesn't gate (see AdminOrderController).
            'next_statuses' => $o->adminSelectableStatuses(),
        ];
    }

    private function formatOrderFull(Order $o): array
    {
        return array_merge($this->formatOrder($o), [
            'guest_name' => $o->guest_name,
            'shipping_name' => $o->shipping_name,
            'shipping_phone' => $o->shipping_phone,
            'shipping_address' => $o->shipping_address,
            'shipping_district' => $o->shipping_district,
            'shipping_division' => $o->shipping_division,
            'shipping_upazila' => $o->shipping_upazila,
            'subtotal' => (float) $o->subtotal,
            'delivery_charge' => (float) $o->delivery_charge,
            'discount' => (float) $o->discount,
            'customer_note' => $o->customer_note,
            'admin_note' => $o->admin_note,
            'prescription_image' => $o->prescription_image ? asset('storage/' . $o->prescription_image) : null,
            'steadfast_consignment_id' => $o->steadfast_consignment_id,
            'steadfast_tracking_code' => $o->steadfast_tracking_code,
            'steadfast_status' => $o->steadfast_status,
            'pathao_consignment_id' => $o->pathao_consignment_id,
            'pathao_tracking_code' => $o->pathao_tracking_code,
            'pathao_status' => $o->pathao_status,
            'held_from_status' => $o->held_from_status,
            'items' => $o->items->map(fn($i) => [
                'id' => $i->id,
                'product_name' => $i->product_name,
                'product_sku' => $i->product_sku,
                'quantity' => $i->quantity,
                'price' => (float) $i->price,
                'subtotal' => (float) $i->subtotal,
                'thumbnail' => $i->product?->thumbnail_url,
            ]),
            'status_history' => $o->statusHistory->map(fn($h) => [
                'status' => $h->status,
                'note' => $h->note,
                'created_at' => $h->created_at->toIso8601String(),
            ]),
        ]);
    }
}
