<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SteadfastService;
use App\Services\PathaoService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product', 'user'])
            ->latest();

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

    public function show(Order $order)
    {
        $order->load(['items.product', 'user', 'statusHistory']);
        return response()->json(['order' => $this->formatOrderFull($order)]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string']);

        $allowed = Order::STATUS_FLOW[$order->status] ?? [];
        if (!in_array($request->status, $allowed)) {
            return response()->json([
                'message' => "Cannot move from {$order->status} to {$request->status}"
            ], 422);
        }

        $order->update(['status' => $request->status]);
        $order->statusHistory()->create([
            'status' => $request->status,
            'note' => $request->note ?? null,
            'changed_by' => auth()->id(),
            'notify_customer' => true,
        ]);

        return response()->json(['message' => 'Status updated', 'status' => $request->status]);
    }

    public function addNote(Request $request, Order $order)
    {
        $request->validate(['note' => 'required|string|max:1000']);
        $order->update(['admin_note' => $request->note]);
        return response()->json(['message' => 'Note saved']);
    }

    public function pushToSteadfast(Order $order)
    {
        try {
            $service = app(SteadfastService::class);
            $result = $service->createOrder($order);
            return response()->json(['message' => 'Pushed to Steadfast', 'result' => $result]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function pushToPathao(Order $order)
    {
        try {
            $service = app(PathaoService::class);
            $result = $service->createOrder($order);
            return response()->json(['message' => 'Pushed to Pathao', 'result' => $result]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function syncCourier(Order $order)
    {
        try {
            if ($order->courier === 'steadfast' && $order->steadfast_consignment_id) {
                $result = app(SteadfastService::class)->getOrderStatus($order->steadfast_consignment_id);
                $order->update(['steadfast_status' => $result['delivery_status'] ?? null]);
            } elseif ($order->courier === 'pathao' && $order->pathao_consignment_id) {
                $result = app(PathaoService::class)->getConsignment($order->pathao_consignment_id);
                $order->update(['pathao_status' => $result['delivery_status'] ?? null]);
            }
            return response()->json(['message' => 'Synced', 'order' => $this->formatOrderFull($order->fresh())]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
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
            'next_statuses' => Order::STATUS_FLOW[$o->status] ?? [],
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