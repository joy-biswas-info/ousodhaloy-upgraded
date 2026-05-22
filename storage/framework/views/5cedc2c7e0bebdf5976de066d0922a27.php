<?php $__env->startSection('title', \App\Models\Setting::get('site_name','Ousodhaloy') . ' – বাংলাদেশের বিশ্বস্ত অনলাইন ফার্মেসি'); ?>

<?php $__env->startSection('content'); ?>


<section class="relative h-3/6 bg-teal-800 overflow-hidden" x-data="slider(<?php echo e($banners->count()); ?>)">
    <div class="relative sm:h-72 md:h-80">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div x-show="current === <?php echo e($i); ?>" x-transition:enter="transition-opacity duration-500"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            class="absolute inset-0 flex items-center"
            style="background: <?php echo e($banner->bg_color); ?>;">
            <div class="max-w-7xl mx-auto px-6 w-full flex items-center justify-between gap-8">
                <div class="text-white max-w-lg">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->badge_text): ?>
                    <span class="inline-block bg-white/20 text-white text-xs font-semibold px-3 py-1 rounded-full mb-3"><?php echo e($banner->badge_text); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-black leading-tight mb-2"><?php echo e($banner->title); ?></h1>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->subtitle): ?>
                    <p class="text-white/80 text-sm sm:text-base mb-4"><?php echo e($banner->subtitle); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->link_url): ?>
                    <a href="<?php echo e($banner->link_url); ?>" class="inline-flex items-center gap-2 bg-white text-teal-800 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-teal-50 transition-colors">
                        <?php echo e($banner->button_text ?? 'Shop Now'); ?> <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->image_url): ?>
                <img src="<?php echo e($banner->image_url); ?>" class="hidden md:block h-64 object-contain" alt="<?php echo e($banner->title); ?>">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="absolute inset-0 bg-gradient-to-r from-teal-900 to-teal-700 flex items-center">
            <div class="max-w-7xl mx-auto px-6 text-white">
                <span class="inline-block bg-white/20 text-xs font-semibold px-3 py-1 rounded-full mb-3">🇧🇩 Trusted Pharmacy</span>
                <h1 class="text-3xl md:text-4xl font-black mb-2">আসল ওষুধ, দ্রুত ডেলিভারি</h1>
                <p class="text-white/80 mb-4">৳500+ অর্ডারে ফ্রি হোম ডেলিভারি সারা বাংলাদেশে</p>
                <a href="<?php echo e(route('shop.index')); ?>" class="inline-flex items-center gap-2 bg-white text-teal-800 font-bold px-5 py-2.5 rounded-xl text-sm hover:bg-teal-50 transition-colors">
                    Shop Now <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banners->count() > 1): ?>
    <button @click="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-left text-xs"></i>
    </button>
    <button @click="next()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-black/30 hover:bg-black/50 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors">
        <i class="fas fa-chevron-right text-xs"></i>
    </button>
    <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button @click="current = <?php echo e($i); ?>" :class="current === <?php echo e($i); ?> ? 'bg-white w-5' : 'bg-white/50 w-1.5'"
            class="h-1.5 rounded-full transition-all duration-300"></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</section>


<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 py-3">
        <div class="flex items-center justify-around gap-2 text-center flex-wrap">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['icon' => 'fas fa-certificate', 'text' => '100% Genuine', 'sub' => 'DGDA Licensed'],
                ['icon' => 'fas fa-truck', 'text' => 'Fast Delivery', 'sub' => '24-48hrs nationwide'],
                ['icon' => 'fas fa-headset', 'text' => '24/7 Support', 'sub' => '09610016778'],
                ['icon' => 'fas fa-shield-alt', 'text' => 'Secure Payment', 'sub' => 'bKash · Nagad · Card'],
                ['icon' => 'fas fa-undo', 'text' => 'Easy Returns', 'sub' => '7-day policy'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-2 py-1">
                <i class="<?php echo e($badge['icon']); ?> text-teal-600 text-lg"></i>
                <div class="text-left">
                    <p class="text-xs font-bold text-gray-800"><?php echo e($badge['text']); ?></p>
                    <p class="text-[10px] text-gray-500"><?php echo e($badge['sub']); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>


<section class="max-w-7xl mx-auto px-4 py-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-black text-gray-800">Shop by Category</h2>
        <a href="<?php echo e(route('shop.index')); ?>" class="text-xs text-teal-700 font-semibold hover:underline">View all →</a>
    </div>
    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-12 gap-2 sm:gap-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories->take(12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('shop.index', ['category' => $cat->slug])); ?>"
            class="flex flex-col items-center gap-1.5 p-2.5 bg-white rounded-xl border hover:border-teal-400 hover:shadow-md transition-all group text-center">
            <div class="text-2xl sm:text-3xl group-hover:scale-110 transition-transform"><?php echo e($cat->icon); ?></div>
            <span class="text-[10px] sm:text-xs font-semibold text-gray-700 leading-tight"><?php echo e(Str::words($cat->name, 2, '')); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->product_count > 0): ?>
            <span class="text-[9px] text-gray-400"><?php echo e($cat->product_count); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashSale->count() > 0): ?>
<section class="bg-gradient-to-r from-orange-600 to-red-600 py-1">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <div class="flex items-center gap-3">
                <div class="bg-white rounded-lg px-3 py-1.5">
                    <span class="text-orange-600 font-black text-sm">⚡ FLASH SALE</span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashDeal): ?>
                <div class="flex items-center gap-1 text-white text-sm font-bold" x-data="countdown('<?php echo e($flashDeal->ends_at->toISOString()); ?>')">
                    Ends in:
                    <span class="bg-black/30 px-2 py-0.5 rounded font-mono" x-text="hours"></span>:
                    <span class="bg-black/30 px-2 py-0.5 rounded font-mono" x-text="minutes"></span>:
                    <span class="bg-black/30 px-2 py-0.5 rounded font-mono" x-text="seconds"></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <a href="<?php echo e(route('shop.index', ['flash_sale' => 1])); ?>" class="text-white text-xs font-semibold hover:underline">See all →</a>
        </div>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $flashSale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('shop.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($promoBanners->count() > 0): ?>
<section class="max-w-7xl mx-auto px-4 py-6">
    <div class="grid grid-cols-1 sm:grid-cols-<?php echo e(min($promoBanners->count(), 3)); ?> gap-4">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $promoBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($b->link_url ?? '#'); ?>"
            class="rounded-xl overflow-hidden flex items-center px-5 py-4 gap-4 min-h-[90px] hover:opacity-90 transition-opacity"
            style="background: <?php echo e($b->bg_color); ?>">
            <div class="text-white flex-1">
                <p class="font-black text-base leading-tight"><?php echo e($b->title); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->subtitle): ?> <p class="text-white/75 text-xs mt-0.5"><?php echo e($b->subtitle); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->button_text): ?> <span class="inline-block mt-2 text-xs font-bold bg-white/20 px-3 py-1 rounded-full"><?php echo e($b->button_text); ?> →</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->image_url): ?> <img src="<?php echo e($b->image_url); ?>" class="h-16 object-contain" alt="<?php echo e($b->title); ?>"> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured->count() > 0): ?>
<section class="max-w-7xl mx-auto px-4 py-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-black text-gray-800">⭐ Featured Products</h2>
        <a href="<?php echo e(route('shop.index', ['featured' => 1])); ?>" class="text-xs text-teal-700 font-semibold hover:underline">View all →</a>
    </div>
    <div class="products-grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $featured->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('shop.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($newArrivals->count() > 0): ?>
<section class="max-w-7xl mx-auto px-4 py-4 pb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-black text-gray-800">🆕 New Arrivals</h2>
        <a href="<?php echo e(route('shop.index')); ?>" class="text-xs text-teal-700 font-semibold hover:underline">View all →</a>
    </div>
    <div class="products-grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $newArrivals->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('shop.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function slider(count) {
    return {
        current: 0,
        count,
        timer: null,
        init() { if (count > 1) this.timer = setInterval(() => this.next(), 5000); },
        next() { this.current = (this.current + 1) % this.count; },
        prev() { this.current = (this.current - 1 + this.count) % this.count; },
    };
}

function countdown(endTime) {
    return {
        hours: '00', minutes: '00', seconds: '00',
        init() {
            const update = () => {
                const diff = new Date(endTime) - new Date();
                if (diff <= 0) { this.hours = this.minutes = this.seconds = '00'; return; }
                const h = Math.floor(diff / 3600000);
                const m = Math.floor((diff % 3600000) / 60000);
                const s = Math.floor((diff % 60000) / 1000);
                this.hours   = String(h).padStart(2,'0');
                this.minutes = String(m).padStart(2,'0');
                this.seconds = String(s).padStart(2,'0');
            };
            update();
            setInterval(update, 1000);
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/home/index.blade.php ENDPATH**/ ?>