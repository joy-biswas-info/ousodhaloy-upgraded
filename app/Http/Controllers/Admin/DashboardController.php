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
        $orderCounts = $this->orderCounts($today);
        $revenueCounts = $this->revenueCounts($today);
        $stockCounts = $this->stockCounts();

        $stats = [
            'total_orders'     => $orderCounts->total,
            'today_orders'     => $orderCounts->today,
            'total_revenue'    => $revenueCounts->total,
            'today_revenue'    => $revenueCounts->today,
            'total_products'   => Product::active()->count(),
            'total_customers'  => User::where('role', 'customer')->count(),
            'pending_orders'   => Order::where('status', 'pending')->count(),
            'low_stock'        => $stockCounts->low_stock,
            'out_of_stock'     => $stockCounts->out_of_stock,
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

    /**
     * Lightweight JSON refresh for the dashboard's stat cards — deliberately cheap
     * (no chart/recent-orders/top-products queries) so it's safe to poll every
     * minute without a full page reload knocking the admin back to the top.
     */
    public function stats()
    {
        $today = today();
        $orderCounts = $this->orderCounts($today);
        $revenueCounts = $this->revenueCounts($today);

        return response()->json([
            'today_orders' => $orderCounts->today,
            'total_orders' => $orderCounts->total,
            'today_revenue' => $revenueCounts->today,
            'total_revenue' => $revenueCounts->total,
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ]);
    }

    /** total + today order counts in one query (no status filter — matches original total_orders/today_orders). */
    private function orderCounts($today)
    {
        return Order::selectRaw('COUNT(*) as total, SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today', [$today->toDateString()])
            ->first();
    }

    /** total + today revenue in one query, excluding cancelled orders (matches original total_revenue/today_revenue). */
    private function revenueCounts($today)
    {
        return Order::whereNotIn('status', ['cancelled'])
            ->selectRaw('SUM(total) as total, SUM(CASE WHEN DATE(created_at) = ? THEN total ELSE 0 END) as today', [$today->toDateString()])
            ->first();
    }

    /** low-stock (active or not, stock > 0) + out-of-stock (active only) counts in one query. */
    private function stockCounts()
    {
        return Product::selectRaw(
            'SUM(CASE WHEN stock <= low_stock_alert AND stock > 0 THEN 1 ELSE 0 END) as low_stock,
             SUM(CASE WHEN stock = 0 AND is_active = 1 THEN 1 ELSE 0 END) as out_of_stock'
        )->first();
    }
}
