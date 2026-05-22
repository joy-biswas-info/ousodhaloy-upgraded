<?php $__env->startSection('title', 'My Wishlist'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">❤️ My Wishlist</h1>

    <?php
        $wishlists = auth()->user()->wishlists()->with('product.brand')->get();
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wishlists->isEmpty()): ?>
    <div class="bg-white rounded-2xl border p-16 text-center">
        <div class="text-7xl mb-4">💔</div>
        <h3 class="font-bold text-gray-700 text-xl mb-2">Your wishlist is empty</h3>
        <p class="text-gray-500 text-sm mb-5">Save products you love to come back to them later.</p>
        <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary">Browse Products</a>
    </div>
    <?php else: ?>
    <div class="products-grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $wishlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wish): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($wish->product && $wish->product->is_active): ?>
            <?php echo $__env->make('shop.partials.product-card-grid', ['product' => $wish->product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/account/wishlist.blade.php ENDPATH**/ ?>