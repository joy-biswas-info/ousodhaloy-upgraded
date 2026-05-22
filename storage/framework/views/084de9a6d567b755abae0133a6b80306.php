<?php $__env->startSection('page-title', 'Orders'); ?>
<?php $__env->startSection('content'); ?>
    <div class="space-y-4">
        
        <div class="bg-white rounded-xl border p-4">
            <form method="GET" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-48">
                    <label class="form-label">Search</label>
                    <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-input"
                        placeholder="Order #, name, phone...">
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Order::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(request('status') === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">Payment</label>
                    <select name="payment_status" class="form-select">
                        <option value="">All</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['unpaid', 'pending', 'paid', 'failed', 'refunded']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($s); ?>" <?php if(request('payment_status') === $s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
                <div>
                    <label class="form-label">From</label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="form-input">
                </div>
                <div>
                    <label class="form-label">To</label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="form-input">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary btn-sm">Filter</button>
                    <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn-outline btn-sm">Reset</a>
                </div>
            </form>
        </div>

        
        <div class="flex gap-1.5 flex-wrap">
            <a href="<?php echo e(route('admin.orders.index')); ?>"
                class="text-xs px-3 py-1.5 rounded-lg font-semibold <?php echo e(!request('status') ? 'bg-teal-600 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50'); ?>">
                All (<?php echo e($statusCounts->sum()); ?>)
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Order::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($statusCounts[$key] ?? 0): ?>
                    <a href="<?php echo e(route('admin.orders.index', ['status' => $key])); ?>"
                        class="text-xs px-3 py-1.5 rounded-lg font-semibold <?php echo e(request('status') === $key ? 'bg-teal-600 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50'); ?>">
                        <?php echo e($label); ?> (<?php echo e($statusCounts[$key]); ?>)
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="flex justify-end mb-2">
            <a href="<?php echo e(route('admin.orders.create')); ?>" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> New Manual Order
            </a>
        </div>
        <form method="POST" action="<?php echo e(route('admin.orders.bulk')); ?>" id="bulk-form">
            <?php echo csrf_field(); ?>
            <div class="flex items-center gap-3 mb-3">
                <select name="action" class="form-select w-40">
                    <option value="">Bulk action</option>
                    <option value="confirm">Confirm All</option>
                    <option value="cancel">Cancel All</option>
                    <option value="export">Export Excel</option>
                </select>
                <button type="submit" class="btn-secondary btn-sm"
                    onclick="return confirm('Apply bulk action?')">Apply</button>
            </div>

            
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all" class="accent-teal-600"></th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><input type="checkbox" name="order_ids[]" value="<?php echo e($order->id); ?>"
                                            class="accent-teal-600 order-cb"></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>"
                                            class="font-mono font-bold text-teal-700 hover:underline text-xs"><?php echo e($order->order_number); ?></a>
                                    </td>
                                    <td>
                                        <p class="font-semibold text-xs text-gray-800"><?php echo e($order->customer_name); ?></p>
                                        <p class="text-[10px] text-gray-500"><?php echo e($order->customer_phone); ?></p>
                                    </td>
                                    <td class="text-xs text-gray-500"><?php echo e($order->items->count()); ?> items</td>
                                    <td class="font-bold text-teal-700 text-sm">৳<?php echo e(number_format($order->total, 0)); ?></td>
                                    <td>
                                        <span
                                            class="text-xs font-semibold capitalize <?php echo e($order->payment_status === 'paid' ? 'text-green-600' : ($order->payment_status === 'failed' ? 'text-red-600' : 'text-yellow-600')); ?>">
                                            <?php echo e(ucfirst($order->payment_status)); ?>

                                        </span>
                                        <p class="text-[10px] text-gray-400 capitalize">
                                            <?php echo e(str_replace('_', ' ', $order->payment_method)); ?></p>
                                    </td>
                                    <td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->pathao_consignment_id): ?>
                                            <span class="text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded font-semibold">🚚
                                                Pathao</span>
                                        <?php elseif($order->steadfast_consignment_id): ?>
                                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded font-semibold">📦
                                                Steadfast</span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-300">—</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td><span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span>
                                    </td>
                                    <td class="text-xs text-gray-500 whitespace-nowrap">
                                        <?php echo e($order->created_at->format('d M, h:i A')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn-secondary btn-sm">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-12 text-gray-400 text-sm">No orders found</td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <?php echo e($orders->withQueryString()->links()); ?>

    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            document.getElementById('select-all').addEventListener('change', function () {
                document.querySelectorAll('.order-cb').forEach(cb => cb.checked = this.checked);
            });
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/orders/index.blade.php ENDPATH**/ ?>