<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $stats = [
            'orders_today' => Order::whereDate('created_at', $today)->count(),
            'orders_pending' => Order::where('status', 'pending')->count(),
            'orders_processing' => Order::whereIn('status', ['confirmed', 'processing', 'ready_to_ship'])->count(),
            'orders_shipped' => Order::whereIn('status', ['shipped', 'out_for_delivery'])->count(),
            'revenue_today' => Order::whereDate('created_at', $today)
                ->whereNotIn('status', ['cancelled', 'refunded'])
                ->sum('total'),
            'cod_pending' => Order::where('payment_method', 'cod')
                ->where('payment_status', 'pending')
                ->whereIn('status', ['shipped', 'out_for_delivery', 'delivered'])
                ->sum('total'),
            'low_stock_count' => Product::whereRaw('stock <= low_stock_alert')->where('is_active', true)->count(),
            'new_orders_1h' => Order::where('created_at', '>=', now()->subHour())->count(),
        ];

        $recent = Order::with('items')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn($o) => $this->orderSummary($o));

        $byStatus = Order::selectRaw('status, count(*) as count')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json(compact('stats', 'recent', 'byStatus'));
    }

    private function orderSummary(Order $o): array
    {
        return [
            'id' => $o->id,
            'order_number' => $o->order_number,
            'customer_name' => $o->customer_name,
            'customer_phone' => $o->customer_phone,
            'status' => $o->status,
            'payment_method' => $o->payment_method,
            'payment_status' => $o->payment_status,
            'total' => (float) $o->total,
            'items_count' => $o->items->count(),
            'created_at' => $o->created_at->toIso8601String(),
        ];
    }
}
