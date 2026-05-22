<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, Product, User, Setting};
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        $stats = [
            'total_orders'     => Order::count(),
            'today_orders'     => Order::whereDate('created_at', $today)->count(),
            'total_revenue'    => Order::whereNotIn('status', ['cancelled'])->sum('total'),
            'today_revenue'    => Order::whereDate('created_at', $today)->whereNotIn('status',['cancelled'])->sum('total'),
            'total_products'   => Product::active()->count(),
            'total_customers'  => User::where('role', 'customer')->count(),
            'pending_orders'   => Order::where('status', 'pending')->count(),
            'low_stock'        => Product::where('stock', '<=', DB::raw('low_stock_alert'))->where('stock', '>', 0)->count(),
            'out_of_stock'     => Product::active()->where('stock', 0)->count(),
        ];

        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')->pluck('count', 'status');

        // Revenue last 30 days
        $revenueChart = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereNotIn('status', ['cancelled'])
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $recentOrders = Order::with('items')->latest()->take(10)->get();
        $lowStockProducts = Product::active()->where('stock', '<=', DB::raw('low_stock_alert'))->orderBy('stock')->take(8)->get();
        $pendingPrescriptions = \App\Models\Prescription::where('status', 'pending')->count();
        $topProducts = Product::orderByDesc('total_sold')->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'ordersByStatus', 'revenueChart',
            'recentOrders', 'lowStockProducts', 'pendingPrescriptions', 'topProducts'
        ));
    }
}
