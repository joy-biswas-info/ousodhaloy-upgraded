<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Setting};
use App\Services\{OrderService, PathaoService, SteadfastService};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private PathaoService $pathao,
        private SteadfastService $steadfast,
    ) {
    }

    // ── List ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = Order::with(['items', 'landingPage'])->latest();

        if ($q = $request->q)
            $query->where(
                fn($sub) =>
                $sub->where('order_number', 'like', "%{$q}%")
                    ->orWhere('shipping_name', 'like', "%{$q}%")
                    ->orWhere('shipping_phone', 'like', "%{$q}%")
                    ->orWhere('guest_phone', 'like', "%{$q}%")
            );

        if ($status = $request->status)
            $query->where('status', $status);
        if ($payment = $request->payment_status)
            $query->where('payment_status', $payment);
        if ($method = $request->payment_method)
            $query->where('payment_method', $method);
        if ($courier = $request->courier)
            $query->where('courier', $courier);
        if ($from = $request->date_from)
            $query->whereDate('created_at', '>=', $from);
        if ($to = $request->date_to)
            $query->whereDate('created_at', '<=', $to);

        $orders = $query->paginate(20)->withQueryString();
        $statusCounts = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')->pluck('count', 'status');
        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    // ── Detail ────────────────────────────────────────────────────────────

    public function show(Order $order)
    {
        $order->load('items.product', 'statusHistory', 'user', 'landingPage');

        $pathaoDefaults = [
            'city' => Setting::get('pathao_default_city_id'),
            'zone' => Setting::get('pathao_default_zone_id'),
            'area' => Setting::get('pathao_default_area_id'),
        ];

        $steadfastEnabled = (bool) (Setting::get('steadfast_api_key') || Setting::get('steadfast_secret_key'));

        return view('admin.orders.show', compact('order', 'pathaoDefaults', 'steadfastEnabled'));
    }

    // ── Status & payment ──────────────────────────────────────────────────

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Order::STATUS_LABELS)),
            'note' => 'nullable|string|max:500',
            'notify_customer' => 'nullable|boolean',
        ]);

        // Neither Pathao nor Steadfast's API exposes a cancel-consignment
        // endpoint, so cancelling a courier-linked order here only updates
        // our own status — the physical package isn't stopped. Require an
        // explicit confirmation naming the courier before allowing it.
        $consignmentId = $order->pathao_consignment_id ?: $order->steadfast_consignment_id;
        $courierName = $order->pathao_consignment_id ? 'Pathao' : ($order->steadfast_consignment_id ? 'Steadfast' : null);
        if ($request->status === 'cancelled' && $consignmentId && !$request->boolean('confirm_courier_cancel')) {
            return back()->with('error', "This order was already pushed to {$courierName} (consignment {$consignmentId}). Cancelling here only updates the order status here — you must also cancel it manually in {$courierName}'s merchant dashboard.");
        }

        $notify = $request->boolean('notify_customer', true);
        $updated = $this->orderService->updateStatus($order, $request->status, $request->note ?? '', $notify);

        if (!$updated) {
            return back()->with('error', "Can't move this order from \"{$order->status_label}\" to \"" . (Order::STATUS_LABELS[$request->status] ?? $request->status) . '".');
        }

        return back()->with('success', 'Order status updated to: ' . (Order::STATUS_LABELS[$request->status] ?? $request->status));
    }

    // Admin-only (see routes/web.php) — undoes a mistaken cancel/return.
    // Deliberately a separate action from updateStatus(), not just another
    // STATUS_FLOW target, so it can never be triggered by a webhook, bulk
    // action, or manager-level request — see OrderService::reopenOrder().
    public function reopen(Request $request, Order $order)
    {
        $request->validate(['note' => 'nullable|string|max:500']);

        $result = $this->orderService->reopenOrder($order, $request->note ?? '');

        return $result['success']
            ? back()->with('success', 'Order reopened — moved back to Processing.')
            : back()->with('error', $result['error']);
    }

    public function updatePayment(Request $request, Order $order)
    {
        $request->validate(['payment_status' => 'required|in:unpaid,pending,paid,failed,refunded']);
        $order->update(['payment_status' => $request->payment_status]);
        return back()->with('success', 'Payment status updated.');
    }

    // ── Admin note ────────────────────────────────────────────────────────

    public function adminNote(Request $request, Order $order)
    {
        $request->validate(['admin_note' => 'nullable|string|max:1000']);
        $order->update(['admin_note' => $request->admin_note]);
        return back()->with('success', 'Note saved.');
    }

    // ── Pathao ────────────────────────────────────────────────────────────

    public function pushToPathao(Order $order)
    {
        if ($order->status !== 'processing') {
            return back()->with('error', "Order must be in \"Processing\" status before pushing to a courier — currently \"{$order->status_label}\".");
        }

        $result = $this->pathao->createOrder($order);

        if ($result['success']) {
            $this->orderService->updateStatus($order, 'ready_to_ship', 'Pushed to Pathao', false);
            return back()->with('success', 'Pushed to Pathao ✅ Consignment: ' . $order->fresh()->pathao_consignment_id);
        }

        return back()->with('error', 'Pathao error: ' . ($result['error'] ?? 'Unknown error'));
    }

    public function pathaoLookup(Request $request)
    {
        $type = $request->type;

        if (!in_array($type, ['cities', 'zones', 'areas'])) {
            return response()->json(['success' => false, 'error' => 'Invalid type'], 400);
        }

        // Require city_id for zones, zone_id for areas
        if ($type === 'zones' && !$request->filled('city_id')) {
            return response()->json(['success' => false, 'error' => 'city_id required'], 422);
        }
        if ($type === 'areas' && !$request->filled('zone_id')) {
            return response()->json(['success' => false, 'error' => 'zone_id required'], 422);
        }

        try {
            // Flush bad cached token so a fresh one is fetched
            $data = match ($type) {
                'cities' => $this->pathao->getCities(),
                'zones' => $this->pathao->getZones((int) $request->city_id),
                'areas' => $this->pathao->getAreas((int) $request->zone_id),
            };

            // Return empty array as success — JS will show "no results" message
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            // Clear cached token so next request gets a fresh one
            \Illuminate\Support\Facades\Cache::forget('pathao_token_' . md5(
                Setting::get('pathao_client_id', '') .
                Setting::get('pathao_username', '')
            ));

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function pathaoSuccessRate(Order $order)
    {
        if (!$order->customer_phone) {
            return response()->json(['success' => false, 'error' => 'This order has no phone number on file.'], 422);
        }

        $result = $this->pathao->getUserSuccessRate($order->customer_phone);
        return response()->json($result, $result['success'] ? 200 : 422);
    }

    public function syncPathao(Order $order)
    {
        $synced = $this->pathao->syncOrderStatus($order);
        return back()->with('success', $synced ? 'Status synced from Pathao.' : 'No update from Pathao.');
    }

    // ── Steadfast ─────────────────────────────────────────────────────────
    public function pushToSteadfast(Order $order)
    {
        if ($order->status !== 'processing') {
            return back()->with('error', "Order must be in \"Processing\" status before pushing to a courier — currently \"{$order->status_label}\".");
        }

        $result = $this->steadfast->createOrder($order);

        if ($result['success']) {
            $this->orderService->updateStatus($order, 'ready_to_ship', 'Pushed to Steadfast', false);
            return back()->with('success', 'Order pushed to Steadfast. Consignment: ' . $order->fresh()->steadfast_consignment_id);
        }

        return back()->with('error', $result['error'] ?? 'Failed to create Steadfast order.');
    }

    public function syncSteadfast(Order $order)
    {
        $synced = $this->steadfast->syncOrderStatus($order);
        return back()->with('success', $synced ? 'Status synced from Steadfast.' : 'No update from Steadfast.');
    }

    // ── Shipping label ────────────────────────────────────────────────────
    public function shippingLabel(Order $order)
    {
        $order->load('items');
        $courier = $order->courier
            ?? ($order->pathao_consignment_id ? 'pathao' : null)
            ?? ($order->steadfast_consignment_id ? 'steadfast' : null);

        if (!$courier) {
            return back()->with('error', 'This order has not been pushed to a courier yet.');
        }
        return view('admin.orders.shipping-label', compact('order', 'courier'));
    }

    // ── Invoice ───────────────────────────────────────────────────────────

    public function invoice(Order $order)
    {
        $order->load('items.product');
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download("Invoice-{$order->order_number}.pdf");
    }

    // ── Bulk actions ──────────────────────────────────────────────────────

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:confirm,cancel,export,shipped,trash',
            'order_ids' => 'required|array',
        ]);
        $orders = Order::whereIn('id', $request->order_ids)->get();

        if ($request->action === 'export') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\OrdersExport($orders),
                'orders.xlsx'
            );
        }

        if ($request->action === 'trash') {
            $count = Order::whereIn('id', $request->order_ids)->delete();
            return back()->with('success', "{$count} order(s) moved to trash.");
        }

        $statusMap = ['confirm' => 'confirmed', 'cancel' => 'cancelled', 'shipped' => 'shipped'];
        $newStatus = $statusMap[$request->action];

        $updated = 0;
        $skipped = 0;
        foreach ($orders as $order) {
            if ($this->orderService->updateStatus($order, $newStatus, 'Bulk action', false)) {
                $updated++;
            } else {
                $skipped++;
            }
        }

        $message = "{$updated} order(s) updated to {$newStatus}.";
        if ($skipped) {
            $message .= " {$skipped} order(s) skipped — status change not valid from their current status.";
        }
        return back()->with('success', $message);
    }

    // ── Steadfast return request (order already left the warehouse) ────────

    public function requestSteadfastReturn(Request $request, Order $order)
    {
        if (!$order->steadfast_consignment_id) {
            return back()->with('error', 'This order has no Steadfast consignment.');
        }

        $request->validate(['reason' => 'nullable|string|max:255']);

        $result = $this->steadfast->createReturnRequest($order->steadfast_consignment_id, $request->reason ?? '');

        return $result['success']
            ? back()->with('success', 'Return request submitted to Steadfast.')
            : back()->with('error', 'Steadfast error: ' . ($result['error'] ?? 'Unknown error'));
    }

    // ── Trash ─────────────────────────────────────────────────────────────

    public function destroy(Order $order)
    {
        $order->delete(); // soft delete — recoverable from Trash
        return redirect()->route('admin.orders.index')->with('success', "Order \"{$order->order_number}\" moved to trash.");
    }

    public function trash()
    {
        $orders = Order::onlyTrashed()->with('items')->latest('deleted_at')->paginate(20);
        return view('admin.orders.trash', compact('orders'));
    }

    public function restore(int $id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        $order->restore();
        return back()->with('success', "Order \"{$order->order_number}\" restored.");
    }

    public function forceDelete(int $id)
    {
        $order = Order::onlyTrashed()->findOrFail($id);
        if ($order->prescription_image) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($order->prescription_image);
        }
        $order->forceDelete();
        return back()->with('success', 'Order permanently deleted.');
    }
}