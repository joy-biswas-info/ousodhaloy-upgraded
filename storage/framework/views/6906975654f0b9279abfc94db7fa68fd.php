<?php $__env->startSection('page-title', 'Order Trash'); ?>
<?php $__env->startSection('breadcrumb', 'Orders / Trash'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500"><?php echo e($orders->total()); ?> deleted order(s) — restore or permanently delete.</p>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn-outline btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Orders
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Deleted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="opacity-75 hover:opacity-100">
                                <td>
                                    <p class="font-mono font-bold text-gray-600 text-xs line-through"><?php echo e($order->order_number); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e($order->items->count()); ?> items</p>
                                </td>
                                <td>
                                    <p class="font-semibold text-xs text-gray-700"><?php echo e($order->customer_name); ?></p>
                                    <p class="text-[10px] text-gray-400"><?php echo e($order->customer_phone); ?></p>
                                </td>
                                <td class="text-sm font-semibold text-gray-600">৳<?php echo e(number_format($order->total, 2)); ?></td>
                                <td><span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span></td>
                                <td class="text-xs text-gray-400"><?php echo e($order->deleted_at->diffForHumans()); ?></td>
                                <td>
                                    <div class="flex gap-1.5">
                                        <form method="POST" action="<?php echo e(route('admin.orders.restore', $order->id)); ?>">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn-secondary btn-sm">
                                                <i class="fas fa-undo mr-1"></i>Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo e(route('admin.orders.force-delete', $order->id)); ?>"
                                            onsubmit="return confirm('Permanently delete order <?php echo e($order->order_number); ?>? This cannot be undone.')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-danger btn-sm">
                                                <i class="fas fa-trash mr-1"></i>Delete Forever
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-16 text-gray-400">
                                    <i class="fas fa-trash-alt text-4xl mb-3 block opacity-30"></i>
                                    <p class="font-semibold">Trash is empty</p>
                                    <p class="text-xs mt-1">Deleted orders appear here</p>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="p-4"><?php echo e($orders->links()); ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/orders/trash.blade.php ENDPATH**/ ?>