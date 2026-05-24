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
        $query = Order::with('items')->latest();

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
        $order->load('items.product', 'statusHistory', 'user');

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
            'status' => 'required|string',
            'note' => 'nullable|string|max:500',
            'notify_customer' => 'nullable|boolean',
        ]);

        $notify = $request->boolean('notify_customer', true);
        $this->orderService->updateStatus($order, $request->status, $request->note ?? '', $notify);

        return back()->with('success', 'Order status updated to: ' . (Order::STATUS_LABELS[$request->status] ?? $request->status));
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
                \App\Models\Setting::get('pathao_client_id', '') .
                \App\Models\Setting::get('pathao_username', '')
            ));

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    public function syncPathao(Order $order)
    {
        $synced = $this->pathao->syncOrderStatus($order);
        return back()->with('success', $synced ? 'Status synced from Pathao.' : 'No update from Pathao.');
    }

    // ── Steadfast ─────────────────────────────────────────────────────────

    public function pushToSteadfast(Order $order)
    {
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
            'action' => 'required|in:confirm,cancel,export',
            'order_ids' => 'required|array',
        ]);

        $orders = Order::whereIn('id', $request->order_ids)->get();

        if ($request->action === 'export') {
            return \Maatwebsite\Excel\Facades\Excel::download(
                new \App\Exports\OrdersExport($orders),
                'orders.xlsx'
            );
        }

        $statusMap = ['confirm' => 'confirmed', 'cancel' => 'cancelled'];
        $newStatus = $statusMap[$request->action];

        foreach ($orders as $order) {
            $this->orderService->updateStatus($order, $newStatus, 'Bulk action', false);
        }

        return back()->with('success', count($orders) . " orders updated to {$newStatus}.");
    }
}