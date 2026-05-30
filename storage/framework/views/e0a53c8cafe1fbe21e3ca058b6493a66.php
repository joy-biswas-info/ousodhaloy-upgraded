
<a href="<?php echo e(route('shop.product', $product->slug)); ?>"
    class="flex items-center gap-3 p-3 rounded-xl hover:bg-teal-50 transition-all group border border-transparent hover:border-gray-200">

    
    <div class="w-12 h-12 flex-shrink-0 rounded-xl overflow-hidden bg-gray-50 border flex items-center justify-center">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->thumbnail_url): ?>
            <img src="<?php echo e($product->thumbnail_url); ?>" alt="<?php echo e($product->name); ?>"
                class="w-full h-full object-contain p-1 group-hover:scale-105 transition-transform duration-200">
        <?php else: ?>
            <span class="text-2xl">💊</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="flex-1 min-w-0">
        <p
            class="text-xs font-medium text-gray-800 leading-tight line-clamp-2 group-hover:text-teal-700 transition-colors">
            <?php echo e($product->name); ?>

        </p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->generic_name): ?>
            <p class="text-xs text-gray-400 mt-0.5 truncate"><?php echo e($product->generic_name); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->strength): ?>
                    · <?php echo e($product->strength); ?>

                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="flex items-center gap-2 mt-1">
            <span class="text-sm font-black"
                style="color:var(--teal)">৳<?php echo e(number_format($product->effective_price, 2)); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->mrp && $product->mrp > $product->effective_price): ?>
                <span class="text-xs text-gray-400 line-through">৳<?php echo e(number_format($product->mrp, 2)); ?></span>
                <span class="text-[10px] font-bold bg-red-100 text-red-600 px-1.5 py-0.5 rounded-md">
                    -<?php echo e($product->discount_percentage); ?>%
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-teal-500 flex-shrink-0 transition-colors"></i>
</a>
<?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/partials/product-card-list.blade.php ENDPATH**/ ?>