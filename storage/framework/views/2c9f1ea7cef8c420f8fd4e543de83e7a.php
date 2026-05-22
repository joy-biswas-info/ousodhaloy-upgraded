<?php $__env->startSection('title', 'My Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">📦 My Orders</h1>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($orders->isEmpty()): ?>
    <div class="bg-white rounded-2xl border p-16 text-center">
        <div class="text-7xl mb-4">📭</div>
        <h3 class="font-bold text-gray-700 text-xl mb-2">No orders yet</h3>
        <p class="text-gray-500 text-sm mb-5">You haven't placed any orders.</p>
        <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary">Browse Products</a>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-2xl border p-5 hover:shadow-md transition-shadow">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                <div>
                    <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="font-bold text-teal-700 hover:underline font-mono">
                        #<?php echo e($order->order_number); ?>

                    </a>
                    <p class="text-xs text-gray-500 mt-0.5"><?php echo e($order->created_at->format('d M Y, h:i A')); ?></p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span>
                    <span class="text-xs font-semibold <?php echo e($order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'); ?> bg-<?php echo e($order->payment_status === 'paid' ? 'green' : 'yellow'); ?>-50 px-2 py-0.5 rounded-full">
                        <?php echo e(ucfirst($order->payment_status)); ?>

                    </span>
                </div>
            </div>

            
            <div class="flex items-center gap-2 mb-3 overflow-x-auto scrollbar-hide pb-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="w-10 h-10 bg-gray-50 rounded-lg border flex-shrink-0 flex items-center justify-center text-lg">💊</div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->items->count() > 4): ?>
                <span class="text-xs text-gray-400 flex-shrink-0">+<?php echo e($order->items->count() - 4); ?> more</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500"><?php echo e($order->items->count()); ?> items · <?php echo e(ucwords(str_replace('_',' ',$order->payment_method))); ?></p>
                    <p class="font-black text-teal-700">৳<?php echo e(number_format($order->total, 2)); ?></p>
                </div>
                <div class="flex gap-2">
                    <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="btn-secondary btn-sm">View Details</a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->pathao_tracking_code): ?>
                    <a href="<?php echo e(route('track')); ?>?order=<?php echo e($order->order_number); ?>" class="btn-outline btn-sm">Track</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="mt-5"><?php echo e($orders->links()); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/orders/my_orders.blade.php ENDPATH**/ ?>