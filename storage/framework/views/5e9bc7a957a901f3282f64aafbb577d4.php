<?php $__env->startSection('title', 'Payment Failed'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-7xl mb-4">😔</div>
        <h1 class="text-2xl font-black text-gray-800 mb-2">Payment Failed</h1>
        <p class="text-gray-500 mb-6">We couldn't process your payment. Don't worry — no money was charged. You can try again or place your order with Cash on Delivery.</p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="<?php echo e(route('checkout.index')); ?>" class="btn-primary">Try Again</a>
            <a href="<?php echo e(route('home')); ?>" class="btn-outline">Go Home</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/checkout/failed.blade.php ENDPATH**/ ?>