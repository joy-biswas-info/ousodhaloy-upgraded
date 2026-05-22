<?php $__env->startSection('title', 'Shopping Cart'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">🛒 Your Cart
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($items)): ?>
        <span class="text-base font-normal text-gray-500">(<?php echo e($totals['item_count']); ?> items)</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </h1>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($items)): ?>
    <div class="bg-white rounded-2xl border p-16 text-center">
        <div class="text-7xl mb-4">🛒</div>
        <h3 class="font-bold text-gray-700 text-xl mb-2">Your cart is empty</h3>
        <p class="text-gray-500 mb-6">Add some products to get started</p>
        <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary">Browse Products</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        
        <div class="lg:col-span-2 space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-xl border p-4 flex gap-4" id="cart-item-<?php echo e($id); ?>">
                <a href="<?php echo e(route('shop.product', $item['product']->slug)); ?>" class="w-20 h-20 flex-shrink-0 bg-gray-50 rounded-xl border flex items-center justify-center overflow-hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['product']->thumbnail): ?>
                        <img src="<?php echo e($item['product']->thumbnail_url); ?>" class="max-w-full max-h-full object-contain">
                    <?php else: ?>
                        <span class="text-3xl">💊</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>
                <div class="flex-1 min-w-0">
                    <a href="<?php echo e(route('shop.product', $item['product']->slug)); ?>" class="font-semibold text-gray-800 text-sm hover:text-teal-700 line-clamp-2"><?php echo e($item['name']); ?></a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['product']->generic_name): ?>
                    <p class="text-xs text-gray-500"><?php echo e($item['product']->generic_name); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex items-center justify-between mt-2 flex-wrap gap-2">
                        <div>
                            <span class="font-black text-teal-700">৳<?php echo e(number_format($item['price'], 2)); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['product']->mrp && $item['product']->mrp > $item['price']): ?>
                            <span class="text-xs text-gray-400 line-through ml-1">৳<?php echo e(number_format($item['product']->mrp, 2)); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex items-center border rounded-lg overflow-hidden">
                                <form action="<?php echo e(route('cart.update', $id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="qty" value="<?php echo e(max(0, $item['qty']-1)); ?>">
                                    <button type="submit" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-50 text-sm font-bold">−</button>
                                </form>
                                <span class="px-3 py-1.5 text-sm font-bold min-w-[2.5rem] text-center"><?php echo e($item['qty']); ?></span>
                                <form action="<?php echo e(route('cart.update', $id)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="qty" value="<?php echo e($item['qty']+1); ?>">
                                    <button type="submit" class="px-2.5 py-1.5 text-gray-600 hover:bg-gray-50 text-sm font-bold">+</button>
                                </form>
                            </div>
                            <span class="font-bold text-gray-800 text-sm">৳<?php echo e(number_format($item['subtotal'], 2)); ?></span>
                            <form action="<?php echo e(route('cart.remove', $id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-400 hover:text-red-600 transition-colors px-1">
                                    <i class="fas fa-trash-alt text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="flex justify-between items-center pt-1">
                <form action="<?php echo e(route('cart.clear')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition-colors">
                        <i class="fas fa-trash-alt mr-1"></i>Clear Cart
                    </button>
                </form>
                <a href="<?php echo e(route('shop.index')); ?>" class="text-xs text-teal-700 hover:underline">
                    ← Continue Shopping
                </a>
            </div>
        </div>

        
        <div class="space-y-4">
            
            <div class="bg-white rounded-xl border p-4" x-data="promoCode()">
                <h3 class="font-bold text-gray-800 text-sm mb-3">🎁 Promo Code</h3>
                <div class="flex gap-2">
                    <input type="text" x-model="code" placeholder="Enter promo code" @keydown.enter="apply()"
                        class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none uppercase focus:border-teal-500">
                    <button @click="apply()" :disabled="loading"
                        class="btn-secondary btn-sm px-4 flex-shrink-0">
                        <span x-show="!loading">Apply</span>
                        <span x-show="loading"><i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                </div>
                <div x-show="message" class="mt-2 text-xs font-semibold"
                    :class="success ? 'text-green-600' : 'text-red-600'" x-text="message"></div>
            </div>

            
            <div class="bg-white rounded-xl border p-4">
                <h3 class="font-bold text-gray-800 text-sm mb-4">Order Summary</h3>
                <div class="space-y-2 text-sm" id="summary">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal (<?php echo e($totals['item_count']); ?> items)</span>
                        <span class="font-semibold">৳<?php echo e(number_format($totals['subtotal'], 2)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Charge</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totals['delivery_charge'] > 0): ?>
                        <span class="font-semibold">৳<?php echo e(number_format($totals['delivery_charge'], 2)); ?></span>
                        <?php else: ?>
                        <span class="font-semibold text-green-600">FREE 🎉</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totals['discount'] > 0): ?>
                    <div class="flex justify-between text-green-600">
                        <span>Promo Discount</span>
                        <span class="font-semibold">−৳<?php echo e(number_format($totals['discount'], 2)); ?></span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="border-t pt-2 flex justify-between text-base font-black">
                        <span>Total</span>
                        <span class="text-teal-700">৳<?php echo e(number_format($totals['total'], 2)); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totals['subtotal'] < \App\Models\Setting::get('free_delivery_min', 500)): ?>
                    <p class="text-xs text-orange-600 bg-orange-50 rounded-lg px-3 py-2">
                        Add ৳<?php echo e(number_format(\App\Models\Setting::get('free_delivery_min', 500) - $totals['subtotal'], 2)); ?> more for FREE delivery!
                    </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <a href="<?php echo e(route('checkout.index')); ?>" class="btn-primary w-full py-3 mt-4 text-center block">
                    <i class="fas fa-lock mr-2"></i>Proceed to Checkout
                </a>
                <div class="flex justify-center gap-3 mt-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9d/Bkash_logo.png/320px-Bkash_logo.png" class="h-5 object-contain opacity-60" alt="bKash">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/74/Nagad-Logo.png" class="h-5 object-contain opacity-60" alt="Nagad">
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function promoCode() {
    return {
        code: '', loading: false, message: '', success: false,
        async apply() {
            if (!this.code) return;
            this.loading = true; this.message = '';
            const res = await fetch('/cart/validate-promo', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ code: this.code })
            });
            const data = await res.json();
            this.loading = false;
            this.success = data.valid;
            this.message = data.message;
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/cart/index.blade.php ENDPATH**/ ?>