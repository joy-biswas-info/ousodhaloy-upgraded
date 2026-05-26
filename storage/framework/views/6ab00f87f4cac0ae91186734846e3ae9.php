<?php $__env->startSection('title', ($currentCat ? $currentCat->name . ' – ' : '') . 'Products'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto px-4 py-5">
        
        <div>
            
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <div>
                    <h1 class="font-bold text-gray-800">
                        <?php echo e($currentCat ? $currentCat->icon . ' ' . $currentCat->name : 'All Products'); ?>

                    </h1>
                    <p class="text-xs text-gray-500"><?php echo e($products->total()); ?> products found</p>
                </div>
                <div class="flex items-center gap-2">
                    <select
                        onchange="window.location='<?php echo e(route('shop.index')); ?>?'+new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)),...{sort:this.value}}).toString()"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none focus:border-teal-500">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'discount' => 'Best Discount', 'top_selling' => 'Top Selling', 'rating' => 'Top Rated']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php if($sort === $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['q', 'category', 'brand', 'flash_sale', 'in_stock', 'no_rx', 'featured', 'min_price', 'max_price'])): ?>
                <div class="flex flex-wrap gap-1.5 mb-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('q')): ?>
                        <span
                            class="flex items-center gap-1 bg-teal-100 text-teal-800 text-xs px-2.5 py-1 rounded-full font-medium">
                            Search: "<?php echo e(request('q')); ?>"
                            <a href="<?php echo e(route('shop.index', request()->except(['q', 'page']))); ?>"
                                class="hover:text-teal-600">&times;</a>
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('category') && $currentCat): ?>
                        <span
                            class="flex items-center gap-1 bg-teal-100 text-teal-800 text-xs px-2.5 py-1 rounded-full font-medium">
                            <?php echo e($currentCat->name); ?>

                            <a href="<?php echo e(route('shop.index', request()->except(['category', 'page']))); ?>">&times;</a>
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <a href="<?php echo e(route('shop.index')); ?>" class="text-xs text-red-600 hover:underline px-2 py-1">Clear all</a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($products->isEmpty()): ?>
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="font-bold text-gray-700 text-lg mb-2">No products found</h3>
                    <p class="text-gray-500 text-sm mb-4">Try different filters or search terms</p>
                    <a href="<?php echo e(route('shop.index')); ?>" class="btn-primary">View All Products</a>
                </div>
            <?php else: ?>
                <div class="products-grid">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo $__env->make('shop.partials.product-card-grid', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="mt-6">
                    <?php echo e($products->links('vendor.pagination.simple')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/products/index.blade.php ENDPATH**/ ?>