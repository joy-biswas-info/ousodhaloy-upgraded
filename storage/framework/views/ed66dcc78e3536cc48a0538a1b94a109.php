<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-5">

        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [['label' => "Today's Orders", 'value' => $stats['today_orders'], 'sub' => number_format($stats['total_orders']) . ' total', 'icon' => 'box', 'color' => 'teal'], ['label' => "Today's Revenue", 'value' => '৳' . number_format($stats['today_revenue']), 'sub' => '৳' . number_format($stats['total_revenue']) . ' total', 'icon' => 'taka-sign', 'color' => 'green'], ['label' => 'Pending Orders', 'value' => $stats['pending_orders'], 'sub' => 'Need attention', 'icon' => 'clock', 'color' => 'yellow'], ['label' => 'Customers', 'value' => number_format($stats['total_customers']), 'sub' => number_format($stats['total_products']) . ' products', 'icon' => 'users', 'color' => 'blue'],]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl border p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wide"><?php echo e($card['label']); ?></p>
                            <p class="text-2xl font-black text-gray-800 mt-1"><?php echo e($card['value']); ?></p>
                            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($card['sub']); ?></p>
                        </div>
                        <div class="w-10 h-10 bg-<?php echo e($card['color']); ?>-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-<?php echo e($card['icon']); ?> text-<?php echo e($card['color']); ?>-600"></i>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            
            <div class="lg:col-span-2 bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4">Revenue – Last 30 Days</h2>
                <canvas id="revenue-chart" height="120"></canvas>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4">Orders by Status</h2>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Order::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $count = $ordersByStatus[$key] ?? 0; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($count > 0): ?>
                            <div class="flex items-center justify-between">
                                <a href="<?php echo e(route('admin.orders.index', ['status' => $key])); ?>"
                                    class="flex items-center gap-2 text-sm hover:text-teal-700 transition-colors">
                                    <span class="status-badge status-<?php echo e($key); ?>"><?php echo e($label); ?></span>
                                </a>
                                <span class="font-bold text-gray-800 text-sm"><?php echo e($count); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-800">Recent Orders</h2>
                    <a href="<?php echo e(route('admin.orders.index')); ?>"
                        class="text-xs text-teal-700 hover:underline font-semibold">View all →</a>
                </div>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>"
                            class="flex items-center justify-between py-2.5 px-3 hover:bg-gray-50 rounded-xl transition-colors group">
                            <div>
                                <p class="text-xs font-bold text-gray-800 group-hover:text-teal-700"><?php echo e($order->order_number); ?>

                                </p>
                                <p class="text-[10px] text-gray-500"><?php echo e($order->customer_name); ?> ·
                                    <?php echo e($order->created_at->diffForHumans()); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-teal-700">৳<?php echo e(number_format($order->total, 0)); ?></p>
                                <span class="status-badge status-<?php echo e($order->status); ?>"
                                    style="font-size:9px"><?php echo e($order->status_label); ?></span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-gray-800">⚠️ Low Stock Alert</h2>
                    <a href="<?php echo e(route('admin.products.index', ['status' => 'low_stock'])); ?>"
                        class="text-xs text-teal-700 hover:underline font-semibold">View all →</a>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('admin.products.edit', $p)); ?>"
                        class="flex items-center justify-between py-2.5 px-3 hover:bg-gray-50 rounded-xl transition-colors">
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-gray-800 truncate"><?php echo e($p->name); ?></p>
                            <p class="text-[10px] text-gray-500"><?php echo e($p->brand?->name); ?></p>
                        </div>
                        <span
                            class="<?php echo e($p->stock <= 5 ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600'); ?> text-xs font-bold px-2.5 py-1 rounded-full ml-2 flex-shrink-0">
                            <?php echo e($p->stock); ?> left
                        </span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-500 text-center py-4">All products are well-stocked ✅</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('admin.products.edit', $p)); ?>"
                                        class="font-semibold text-gray-800 hover:text-teal-700"><?php echo e($p->name); ?></a>
                                    <p class="text-xs text-gray-400"><?php echo e($p->generic_name); ?></p>
                                </td>
                                <td class="text-xs text-gray-500"><?php echo e($p->category?->name ?? '—'); ?></td>
                                <td class="font-semibold text-teal-700">৳<?php echo e(number_format($p->price, 2)); ?></td>
                                <td>
                                    <span
                                        class="<?php echo e($p->stock <= $p->low_stock_alert ? 'text-red-600 font-bold' : 'text-gray-700'); ?>"><?php echo e($p->stock); ?></span>
                                </td>
                                <td class="font-bold text-gray-800"><?php echo e(number_format($p->total_sold)); ?></td>
                                <td>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->rating_count > 0): ?>
                                        <span class="text-yellow-500">★</span> <?php echo e($p->average_rating); ?>

                                        <span class="text-xs text-gray-400">(<?php echo e($p->rating_count); ?>)</span>
                                    <?php else: ?> <span class="text-gray-400 text-xs">No reviews</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const chartData = <?php echo json_encode($revenueChart, 15, 512) ?>;
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>