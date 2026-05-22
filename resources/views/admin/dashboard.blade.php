@extends('layouts.admin')
@section('page-title', 'Dashboard')

@section('content')
    <div class="space-y-5">

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach([['label' => "Today's Orders", 'value' => $stats['today_orders'], 'sub' => number_format($stats['total_orders']) . ' total', 'icon' => 'box', 'color' => 'teal'], ['label' => "Today's Revenue", 'value' => '৳' . number_format($stats['today_revenue']), 'sub' => '৳' . number_format($stats['total_revenue']) . ' total', 'icon' => 'taka-sign', 'color' => 'green'], ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'sub' => 'Need attention', 'icon' => 'clock', 'color' => 'yellow'], ['label' => 'Customers', 'value' => number_format($stats['total_customers']), 'sub' => number_format($stats['total_products']) . ' products', 'icon' => 'users', 'color' => 'blue'],] as $card)
                <div class="bg-white rounded-xl border p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide">{{ $card['label'] }}</p>
                            <p class="text-2xl font-black text-gray-800 mt-1">{{ $card['value'] }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $card['sub'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-{{ $card['color'] }}-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-{{ $card['icon'] }} text-{{ $card['color'] }}-600"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Revenue chart --}}
            <div class="lg:col-span-2 bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4">Revenue – Last 30 Days</h2>
                <canvas id="revenue-chart" height="120"></canvas>
            </div>

            {{-- Order status breakdown --}}
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4">Orders by Status</h2>
                <div class="space-y-2">
                    @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                        @php $count = $ordersByStatus[$key] ?? 0; @endphp
                        @if($count > 0)
                            <div class="flex items-center justify-between">
                                <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
                                    class="flex items-center gap-2 text-sm hover:text-teal-700 transition-colors">
                                    <span class="status-badge status-{{ $key }}">{{ $label }}</span>
                                </a>
                                <span class="font-bold text-gray-800 text-sm">{{ $count }}</span>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Recent orders --}}
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-800">Recent Orders</h2>
                    <a href="{{ route('admin.orders.index') }}"
                        class="text-xs text-teal-700 hover:underline font-semibold">View all →</a>
                </div>
                <div class="space-y-2">
                    @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}"
                            class="flex items-center justify-between py-2.5 px-3 hover:bg-gray-50 rounded-xl transition-colors group">
                            <div>
                                <p class="text-xs font-bold text-gray-800 group-hover:text-teal-700">{{ $order->order_number }}
                                </p>
                                <p class="text-[10px] text-gray-500">{{ $order->customer_name }} ·
                                    {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-teal-700">৳{{ number_format($order->total, 0) }}</p>
                                <span class="status-badge status-{{ $order->status }}"
                                    style="font-size:9px">{{ $order->status_label }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Low stock --}}
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-800">⚠️ Low Stock Alert</h2>
                    <a href="{{ route('admin.products.index', ['status' => 'low_stock']) }}"
                        class="text-xs text-teal-700 hover:underline font-semibold">View all →</a>
                </div>
                @forelse($lowStockProducts as $p)
                    <a href="{{ route('admin.products.edit', $p) }}"
                        class="flex items-center justify-between py-2.5 px-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $p->name }}</p>
                            <p class="text-[10px] text-gray-500">{{ $p->brand?->name }}</p>
                        </div>
                        <span
                            class="{{ $p->stock <= 5 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600' }} text-xs font-bold px-2.5 py-1 rounded-full ml-2 flex-shrink-0">
                            {{ $p->stock }} left
                        </span>
                    </a>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">All products are well-stocked ✅</p>
                @endforelse
            </div>
        </div>

        {{-- Top products --}}
        <div class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4">🏆 Top Selling Products</h2>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Total Sold</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $p)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.products.edit', $p) }}"
                                        class="font-semibold text-gray-800 hover:text-teal-700">{{ $p->name }}</a>
                                    <p class="text-xs text-gray-400">{{ $p->generic_name }}</p>
                                </td>
                                <td class="text-xs text-gray-500">{{ $p->category?->name ?? '—' }}</td>
                                <td class="font-semibold text-teal-700">৳{{ number_format($p->price, 2) }}</td>
                                <td>
                                    <span
                                        class="{{ $p->stock <= $p->low_stock_alert ? 'text-red-600 font-bold' : 'text-gray-700' }}">{{ $p->stock }}</span>
                                </td>
                                <td class="font-bold text-gray-800">{{ number_format($p->total_sold) }}</td>
                                <td>
                                    @if($p->rating_count > 0)
                                        <span class="text-yellow-500">★</span> {{ $p->average_rating }}
                                        <span class="text-xs text-gray-400">({{ $p->rating_count }})</span>
                                    @else <span class="text-gray-400 text-xs">No reviews</span> @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const chartData = @json($revenueChart);
        const labels = chartData.map(d => {
            const date = new Date(d.date);
            return date.toLocaleDateString('en-BD', { day: 'numeric', month: 'short' });
        });
        const revenues = chartData.map(d => parseFloat(d.revenue) || 0);

        new Chart(document.getElementById('revenue-chart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Revenue (৳)',
                    data: revenues,
                    backgroundColor: 'rgba(14,118,115,0.15)',
                    borderColor: '#0e7673',
                    borderWidth: 2,
                    borderRadius: 5,
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0' },
                        ticks: { callback: v => '৳' + v.toLocaleString(), font: { size: 11 } }
                    },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });

        // Auto-refresh stats every 60s
        setTimeout(() => location.reload(), 60000);
    </script>
@endpush