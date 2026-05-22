<div class="product-card">
    <a href="<?php echo e(route('shop.product', $product->slug)); ?>" class="block">
        <div class="card-img">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->thumbnail): ?>
                <img src="<?php echo e($product->thumbnail_url); ?>" alt="<?php echo e($product->name); ?>" class="max-h-full object-contain" loading="lazy">
            <?php else: ?>
                <span class="text-5xl">💊</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discount_percentage > 0): ?>
                <span class="badge-discount">-<?php echo e($product->discount_percentage); ?>%</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_flash_sale && $product->flash_sale_ends_at?->isFuture()): ?>
                <span class="badge-flash">⚡</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->requires_prescription): ?>
                <span class="badge-rx" style="top: <?php echo e($product->discount_percentage > 0 ? '26px' : '8px'); ?>">Rx</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->express_delivery): ?>
                <span class="badge-express">🚀 Express</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$product->is_in_stock): ?>
                <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">Out of Stock</span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </a>
    <div class="card-body">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->brand): ?>
            <p class="card-brand"><?php echo e($product->brand->name); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <a href="<?php echo e(route('shop.product', $product->slug)); ?>">
            <h3 class="card-name"><?php echo e($product->name); ?></h3>
        </a>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->generic_name): ?>
            <p class="card-generic"><?php echo e($product->generic_name); ?><?php echo e($product->strength ? ' · '.$product->strength : ''); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="card-price">
            <span class="price-now">৳<?php echo e(number_format($product->effective_price, 2)); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->mrp && $product->mrp > $product->effective_price): ?>
                <span class="price-was">৳<?php echo e(number_format($product->mrp, 2)); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->average_rating > 0): ?>
        <div class="flex items-center gap-1 mb-1">
            <div class="flex">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 1; $i <= 5; $i++): ?>
                <i class="fas fa-star text-[10px] <?php echo e($i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-200'); ?>"></i>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <span class="text-[10px] text-gray-400">(<?php echo e($product->rating_count); ?>)</span>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_low_stock): ?>
            <p class="text-[10px] text-orange-600 font-semibold mb-1">⚠ Only <?php echo e($product->stock); ?> left</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->pack_size): ?>
            <p class="text-[10px] text-gray-400 mb-1"><?php echo e($product->pack_size); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <button
            <?php if($product->is_in_stock && !$product->requires_prescription): ?>
                onclick="addToCart(<?php echo e($product->id); ?>)"
            <?php elseif($product->requires_prescription): ?>
                onclick="window.location='<?php echo e(route('shop.product', $product->slug)); ?>'"
            <?php else: ?>
                disabled
            <?php endif; ?>
            class="card-add-btn">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$product->is_in_stock): ?>
                Out of Stock
            <?php elseif($product->requires_prescription): ?>
                <i class="fas fa-file-prescription text-[10px] mr-1"></i>View (Rx)
            <?php else: ?>
                <i class="fas fa-cart-plus text-[10px] mr-1"></i>Add to Cart
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </button>
    </div>
</div>
<?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/partials/product-card-grid.blade.php ENDPATH**/ ?>