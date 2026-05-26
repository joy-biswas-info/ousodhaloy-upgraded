<?php
    $pixelViewContent = \App\Models\Setting::get('meta_pixel_view_content','true') === 'true';
?>
<?php $__env->startSection('title', $product->name . ' – ' . ($product->generic_name ?? '') . ' – ' . \App\Models\Setting::get('site_name')); ?>
<?php $__env->startSection('meta_description', $product->meta_description ?? "Buy {$product->name} online. {$product->generic_name} {$product->strength}. Fast delivery in Bangladesh."); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-5">

    
    <nav class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-teal-700">Home</a>
        <span>/</span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->category): ?>
        <a href="<?php echo e(route('shop.index', ['category' => $product->category->slug])); ?>" class="hover:text-teal-700"><?php echo e($product->category->name); ?></a>
        <span>/</span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <span class="text-gray-700 font-medium truncate"><?php echo e($product->name); ?></span>
    </nav>

    
    <div class="bg-white rounded-2xl border p-5 mb-5" x-data="productPage()">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            
            <div>
                <div class="bg-gray-50 rounded-xl h-72 sm:h-80 flex items-center justify-center overflow-hidden mb-3 border" x-on:click="lightbox = true">
                    <template x-if="activeImg">
                        <img :src="activeImg" alt="<?php echo e($product->name); ?>" class="max-h-full max-w-full object-contain cursor-zoom-in">
                    </template>
                    <template x-if="!activeImg">
                        <span class="text-8xl">💊</span>
                    </template>
                </div>
                <?php $allImages = array_filter(array_merge([$product->thumbnail_url], $product->image_urls)); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($allImages) > 1): ?>
                <div class="flex gap-2 overflow-x-auto scrollbar-hide">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $imgUrl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeImg = '<?php echo e($imgUrl); ?>'"
                        :class="activeImg === '<?php echo e($imgUrl); ?>' ? 'border-teal-500' : 'border-gray-200'"
                        class="w-14 h-14 flex-shrink-0 border-2 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center transition-colors">
                        <img src="<?php echo e($imgUrl); ?>" alt="" class="max-h-full max-w-full object-contain">
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->brand): ?>
                <a href="<?php echo e(route('shop.index', ['brand' => $product->brand->slug])); ?>" class="text-xs text-teal-600 font-semibold uppercase tracking-wide hover:underline"><?php echo e($product->brand->name); ?></a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 mt-1 leading-tight"><?php echo e($product->name); ?></h1>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->generic_name): ?>
                <p class="text-sm text-gray-500 mt-1"><?php echo e($product->generic_name); ?> <?php echo e($product->strength); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->rating_count > 0): ?>
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex gap-0.5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1; $i<=5; $i++): ?>
                        <i class="fas fa-star text-sm <?php echo e($i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-200'); ?>"></i>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <span class="text-xs text-gray-500"><?php echo e($product->average_rating); ?> (<?php echo e($product->rating_count); ?> reviews)</span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-3xl font-black text-teal-700">৳<?php echo e(number_format($product->effective_price, 2)); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->mrp && $product->mrp > $product->effective_price): ?>
                            <span class="text-base text-gray-400 line-through">৳<?php echo e(number_format($product->mrp, 2)); ?></span>
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full"><?php echo e($product->discount_percentage); ?>% OFF</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_flash_sale && $product->flash_sale_ends_at?->isFuture()): ?>
                    <p class="text-xs text-orange-600 font-semibold mt-1">
                        ⚡ Flash sale ends <?php echo e($product->flash_sale_ends_at->diffForHumans()); ?>

                    </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->pack_size): ?>
                    <p class="text-xs text-gray-500 mt-1">Per <?php echo e($product->unit); ?>: <?php echo e($product->pack_size); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="flex flex-wrap gap-1.5 mt-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->requires_prescription): ?>
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">💊 Prescription Required</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->express_delivery): ?>
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">🚀 Express Delivery</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_flash_sale): ?>
                    <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">⚡ Flash Sale</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_in_stock): ?>
                    <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2.5 py-1 rounded-full">✅ In Stock</span>
                    <?php else: ?>
                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">❌ Out of Stock</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_in_stock): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->requires_prescription): ?>
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700">
                    <p class="font-bold mb-1"><i class="fas fa-file-prescription mr-1"></i>Prescription Required</p>
                    <p class="text-xs mb-3">This medicine requires a valid prescription from a registered physician. Please upload your prescription at checkout.</p>
                    <button onclick="addToCart(<?php echo e($product->id); ?>)" class="btn-primary btn-sm">
                        <i class="fas fa-cart-plus mr-1"></i>Add to Cart (Upload Rx at checkout)
                    </button>
                </div>
                <?php else: ?>
                <div class="mt-4 flex items-center gap-3">
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button @click="qty = Math.max(<?php echo e($product->min_order_qty); ?>, qty-1)" class="px-3 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg leading-none">−</button>
                        <span x-text="qty" class="px-4 py-2.5 font-bold text-sm min-w-[3rem] text-center"></span>
                        <button @click="qty = Math.min(<?php echo e(min($product->max_order_qty, $product->stock)); ?>, qty+1)" class="px-3 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg leading-none">+</button>
                    </div>
                    <button @click="addToCartWithQty(<?php echo e($product->id); ?>, qty)" class="btn-primary flex-1 py-3">
                        <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <button class="btn-outline px-3 py-3" title="Add to wishlist">
                        <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors"></i>
                    </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <a href="<?php echo e(route('checkout.index')); ?>" onclick="addToCart(<?php echo e($product->id); ?>); return true;"
                    class="btn-secondary w-full text-center mt-2 py-3 block">
                    <i class="fas fa-bolt mr-1"></i>Buy Now
                </a>
                <?php else: ?>
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 text-center">
                    <i class="fas fa-times-circle mr-1"></i>Out of Stock — check back soon
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="mt-4 space-y-1.5 text-xs text-gray-500 border-t pt-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->sku): ?> <p><span class="font-semibold text-gray-700">SKU:</span> <?php echo e($product->sku); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->form): ?> <p><span class="font-semibold text-gray-700">Form:</span> <?php echo e($product->form); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->category): ?> <p><span class="font-semibold text-gray-700">Category:</span> <a href="<?php echo e(route('shop.index', ['category' => $product->category->slug])); ?>" class="text-teal-600 hover:underline"><?php echo e($product->category->name); ?></a></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p><span class="font-semibold text-gray-700">Views:</span> <?php echo e(number_format($product->views)); ?></p>
                </div>

                
                <div class="mt-3 bg-teal-50 rounded-xl p-3 text-xs text-teal-700 space-y-1">
                    <p><i class="fas fa-truck mr-1.5"></i>Free delivery on orders above ৳<?php echo e(number_format(\App\Models\Setting::get('free_delivery_min', 500))); ?></p>
                    <p><i class="fas fa-certificate mr-1.5"></i>100% genuine product, DGDA licensed</p>
                    <p><i class="fas fa-undo mr-1.5"></i>Easy 7-day return policy</p>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->tabs && count($product->tabs) > 0): ?>
    <div class="bg-white rounded-2xl border mb-5" x-data="{ activeTab: 0 }">
        <div class="flex border-b overflow-x-auto scrollbar-hide">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $product->tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(trim(strip_tags($tab['content'] ?? ''))): ?>
            <button @click="activeTab = <?php echo e($i); ?>"
                :class="activeTab === <?php echo e($i); ?> ? 'border-b-2 border-teal-600 text-teal-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-3.5 text-sm font-medium whitespace-nowrap transition-colors flex-shrink-0">
                <?php echo e($tab['label']); ?>

            </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $product->tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(trim(strip_tags($tab['content'] ?? ''))): ?>
        <div x-show="activeTab === <?php echo e($i); ?>" class="p-5 prose max-w-none">
            <?php echo $tab['content']; ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-white rounded-2xl border mb-5 p-5">
        <h2 class="font-bold text-gray-800 text-lg mb-4">Customer Reviews
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->rating_count > 0): ?>
            <span class="text-sm font-normal text-gray-500">(<?php echo e($product->rating_count); ?>)</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="border-b last:border-0 py-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="flex gap-0.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1;$i<=5;$i++): ?>
                            <i class="fas fa-star text-xs <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-200'); ?>"></i>
                            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->title): ?> <span class="text-sm font-semibold text-gray-800"><?php echo e($review->title); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->body): ?> <p class="text-sm text-gray-600"><?php echo e($review->body); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="text-right text-xs text-gray-400 flex-shrink-0">
                    <p><?php echo e($review->user->name); ?></p>
                    <p><?php echo e($review->created_at->format('d M Y')); ?></p>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-sm text-gray-500 py-4 text-center">No reviews yet. Be the first to review!</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($related->count() > 0): ?>
    <div class="mb-5">
        <h2 class="font-bold text-gray-800 text-lg mb-4">Similar Products</h2>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('shop.partials.product-card', ['product' => $p], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Meta Pixel — ViewContent
<?php if($pixelViewContent ?? false): ?>
document.addEventListener('DOMContentLoaded', () => {
    if (window.fbTrack) {
        window.fbTrack('ViewContent', {
            content_ids: ['<?php echo e($product->id); ?>'],
            content_name: '<?php echo e(addslashes($product->name)); ?>',
            content_type: 'product',
            value: <?php echo e($product->effective_price); ?>,
            currency: 'BDT'
        });
    }
});
<?php endif; ?>

function productPage() {
    return {
        qty: <?php echo e($product->min_order_qty); ?>,
        activeImg: '<?php echo e($product->thumbnail_url); ?>',
        addToCartWithQty(id, qty) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ product_id: id, qty })
            })
            .then(r => {
                if (!r.ok && r.status !== 422) throw new Error('Server error ' + r.status);
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    // Update ALL cart count badges (desktop + mobile)
                    document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(el => {
                        el.textContent = data.count;
                        el.style.display = 'flex';
                    });
                    showToast(data.message || 'Added to cart!');
                } else {
                    showToast(data.message || 'Could not add to cart', 'error');
                }
            })
            .catch(err => showToast('Network error — please try again', 'error'));
        }
    };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/products/show.blade.php ENDPATH**/ ?>