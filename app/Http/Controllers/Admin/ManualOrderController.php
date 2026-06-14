<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, User, Category, Brand, Setting, DeliveryZone};
use App\Services\OrderService;
use Illuminate\Http\Request;

class ManualOrderController extends Controller
{
    public function create()
    {
        $categories = Category::active()->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $customers = User::where('role', 'customer')->orderBy('name')->get();
        $divisions = config('bd.divisions', []);
        return view('admin.orders.create', compact('categories', 'brands', 'customers', 'divisions'));
    }

    public function productSearch(Request $request)
    {
        $products = Product::active()
            ->inStock()
            ->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%')
                    ->orWhere('generic_name', 'like', '%' . $request->q . '%')
                    ->orWhere('sku', 'like', '%' . $request->q . '%');
            })
            ->with('brand')
            ->take(10)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'generic_name' => $p->generic_name,
                'sku' => $p->sku,
                'price' => $p->effective_price,
                'stock' => $p->stock,
                'unit' => $p->unit,
                'brand' => $p->brand?->name,
                'thumbnail' => $p->thumbnail_url,
            ]);
        return response()->json($products);
    }

    public function store(Request $request, OrderService $orderService)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:100',
            'shipping_phone' => 'required|string|max:15',
            'shipping_division' => 'required|string',
            'shipping_district' => 'required|string',
            'shipping_upazila' => 'nullable|string',
            'shipping_address' => 'required|string|max:500',
            'shipping_postcode' => 'nullable|string|max:10',
            'payment_method' => 'required|in:cod,ssl_commerz,bkash,nagad,bank',
            'payment_status' => 'required|in:unpaid,paid',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $userId = $request->user_id ?: null;

        // Build items with custom price support
        $items = collect($request->items)->map(fn($i) => [
            'product_id' => $i['product_id'],
            'qty' => (int) $i['qty'],
            'custom_price' => (float) $i['price'],
        ])->toArray();

        // Calculate totals manually (admin can override price)
        $subtotal = collect($request->items)->sum(fn($i) => $i['price'] * $i['qty']);
        $discount = (float) ($request->discount ?? 0);
        $delivery = (float) ($request->delivery_charge ?? 0);
        $total = $subtotal + $delivery - $discount;

        $order = \App\Models\Order::create([
            'user_id' => $userId,
            'guest_name' => !$userId ? $request->shipping_name : null,
            'guest_phone' => !$userId ? $request->shipping_phone : null,
            'status' => $request->status ?? 'confirmed',
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'subtotal' => $subtotal,
            'delivery_charge' => $delivery,
            'discount' => $discount,
            'total' => max(0, $total),
            'promo_code' => $request->promo_code ?: null,
            'shipping_name' => $request->shipping_name,
            'shipping_phone' => $request->shipping_phone,
            'shipping_email' => $request->shipping_email,
            'shipping_division' => $request->shipping_division,
            'shipping_district' => $request->shipping_district,
            'shipping_upazila' => $request->shipping_upazila,
            'shipping_address' => $request->shipping_address,
            'shipping_postcode' => $request->shipping_postcode,
            'admin_note' => $request->admin_note,
            'customer_note' => $request->customer_note,
        ]);

        // Create items & deduct stock
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            \App\Models\OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku,
                'price' => $item['price'],
                'mrp' => $product->mrp,
                'quantity' => $item['qty'],
                'subtotal' => $item['price'] * $item['qty'],
            ]);
            if ($request->deduct_stock) {
                $product->decrementStock($item['qty']);
            }
        }

        // Status history
        \App\Models\OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'note' => 'Order created manually by admin',
            'changed_by' => auth()->user()->name,
        ]);

        // SMS if phone provided
        if ($request->send_sms) {
            app(\App\Services\SmsService::class)->orderPlaced($order);
        }

        return redirect()->route('admin.orders.show', $order)
            ->with('success', "Order #{$order->order_number} created successfully!");
    }
}