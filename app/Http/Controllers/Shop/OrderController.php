<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\PathaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function show(int $id)
    {
        $order = Order::with(['items.product', 'statusHistory'])
            ->where(function ($q) use ($id) {
                $q->where('id', $id);
                if (Auth::check())
                    $q->orWhere('user_id', Auth::id());
            })->firstOrFail();

        if ($order->user_id && Auth::check() && Auth::id() !== $order->user_id && !Auth::user()->isManager()) {
            abort(403);
        }

        return view('shop.orders.show', compact('order'));
    }

    public function myOrders()
    {
        // No $this->middleware() — handled by route middleware in web.php
        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);
        return view('shop.orders.my_orders', compact('orders'));
    }

    public function track(Request $request)
    {
        $order = null;
        $error = null;

        if ($request->isMethod('post')) {
            $request->validate(['order_number' => 'required', 'phone' => 'required']);

            $order = Order::where('order_number', $request->order_number)
                ->where(function ($q) use ($request) {
                    $q->where('shipping_phone', $request->phone)
                        ->orWhere('guest_phone', $request->phone);
                })
                ->with(['items', 'statusHistory'])
                ->first();

            if (!$order) {
                $error = 'No order found. Please check your order number and phone number.';
            } else {
                app(PathaoService::class)->syncOrderStatus($order);
                $order->refresh();
            }
        }

        return view('shop.orders.track', compact('order', 'error'));
    }
}

