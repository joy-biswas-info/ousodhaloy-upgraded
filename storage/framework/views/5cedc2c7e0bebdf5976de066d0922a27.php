<?php $__env->startSection('title', \App\Models\Setting::get('site_name','Ousodhaloy') . ' – বাংলাদেশের বিশ্বস্ত অনলাইন ফার্মেসি'); ?>

<?php $__env->startSection('content'); ?>


<section x-data="heroSlider(<?php echo e($banners->count()); ?>)" style="position:relative;overflow:hidden;background:var(--teal-dark)">
    <?php $heroH = (int)\App\Models\Setting::get('hero_banner_height', 400); ?>
    <div style="position:relative;min-height:240px;height:<?php echo e($heroH); ?>px;max-height:<?php echo e($heroH); ?>px;">

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div x-show="current === <?php echo e($i); ?>"
            x-transition:enter="transition-opacity duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            style="position:absolute;inset:0;background:<?php echo e($banner->bg_color); ?>">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->image_url): ?>
            <img src="<?php echo e($banner->image_url); ?>" alt="<?php echo e($banner->title); ?>"
                style="width:100%;height:100%;object-fit:cover;display:block">
            <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(0,0,0,.55) 0%,rgba(0,0,0,.15) 55%,transparent 100%)"></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div style="position:absolute;inset:0;display:flex;align-items:center;padding:0 32px">
                <div style="color:#fff;max-width:480px">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->badge_text): ?>
                    <span style="display:inline-block;background:rgba(255,255,255,.2);font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;margin-bottom:10px"><?php echo e($banner->badge_text); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <h1 style="font-size:clamp(20px,4vw,36px);font-weight:900;line-height:1.2;margin-bottom:8px;text-shadow:0 1px 4px rgba(0,0,0,.3)"><?php echo e($banner->title); ?></h1>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->subtitle): ?>
                    <p style="font-size:14px;opacity:.88;margin-bottom:16px;text-shadow:0 1px 3px rgba(0,0,0,.2)"><?php echo e($banner->subtitle); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->link_url): ?>
                    <a href="<?php echo e($banner->link_url); ?>" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:var(--teal-dark);font-weight:700;font-size:13px;padding:10px 22px;border-radius:10px;text-decoration:none;transition:transform .15s" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform=''">
                        <?php echo e($banner->button_text ?? 'Shop Now'); ?> <i class="fas fa-arrow-right" style="font-size:11px"></i>
                    </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banners->count() > 1): ?>
    <button @click="prev()" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.3);color:#fff;width:34px;height:34px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s" onmouseover="this.style.background='rgba(0,0,0,.55)'" onmouseout="this.style.background='rgba(0,0,0,.3)'">
        <i class="fas fa-chevron-left" style="font-size:12px"></i>
    </button>
    <button @click="next()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.3);color:#fff;width:34px;height:34px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s" onmouseover="this.style.background='rgba(0,0,0,.55)'" onmouseout="this.style.background='rgba(0,0,0,.3)'">
        <i class="fas fa-chevron-right" style="font-size:12px"></i>
    </button>
    <div style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);display:flex;gap:6px">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button @click="current=<?php echo e($i); ?>"
            :style="current===<?php echo e($i); ?> ? 'width:20px;background:#fff;opacity:1' : 'width:6px;background:#fff;opacity:.5'"
            style="height:6px;border-radius:3px;border:none;cursor:pointer;transition:all .3s"></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</section>


<!-- <div style="background:#fff;border-bottom:1px solid #e5e7eb">
    <div style="max-width:900px;margin:0 auto;padding:10px 16px">
        <div style="display:flex;align-items:center;justify-content:space-around;flex-wrap:wrap;gap:8px">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['fas fa-certificate','100% Genuine','DGDA Licensed'],
                ['fas fa-truck','Fast Delivery','24-48hrs'],
                ['fas fa-headset','24/7 Support','Always here'],
                ['fas fa-shield-alt','Secure Pay','bKash · Card'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$icon, $text, $sub]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div style="display:flex;align-items:center;gap:8px;padding:4px 0">
                <i class="<?php echo e($icon); ?>" style="color:var(--teal);font-size:15px"></i>
                <div>
                    <p style="font-size:12px;font-weight:700;color:#1f2937;margin:0"><?php echo e($text); ?></p>
                    <p style="font-size:10px;color:#9ca3af;margin:0"><?php echo e($sub); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div> -->


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($promoBanners->count() > 0): ?>
<div style="padding:16px 16px 0">
    <div style="display:grid;grid-template-columns:repeat(<?php echo e(min($promoBanners->count(),3)); ?>,1fr);gap:10px">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $promoBanners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e($b->link_url ?? '#'); ?>"
            style="border-radius:12px;overflow:hidden;display:flex;align-items:center;padding:16px;gap:12px;min-height:80px;text-decoration:none;background:<?php echo e($b->bg_color); ?>;transition:opacity .15s"
            onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
            <div style="color:#fff;flex:1">
                <p style="font-weight:900;font-size:15px;line-height:1.3;margin:0"><?php echo e($b->title); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->subtitle): ?><p style="font-size:11px;opacity:.8;margin:3px 0 0"><?php echo e($b->subtitle); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->button_text): ?><span style="display:inline-block;margin-top:8px;font-size:11px;font-weight:700;background:rgba(255,255,255,.2);padding:3px 10px;border-radius:20px"><?php echo e($b->button_text); ?> →</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($b->image_url): ?><img src="<?php echo e($b->image_url); ?>" style="height:56px;object-fit:contain" alt="<?php echo e($b->title); ?>"><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashSale->count() > 0): ?>
<div style="padding:16px">
    <div style="border-radius:14px;overflow:hidden;background:linear-gradient(135deg,#ea580c,#dc2626)">
        <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:12px">
                <span style="background:#fff;color:#ea580c;font-weight:900;font-size:13px;padding:6px 12px;border-radius:8px">⚡ FLASH SALE</span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flashDeal): ?>
                <div style="display:flex;align-items:center;gap:4px;color:#fff;font-size:13px;font-weight:700"
                    x-data="countdown('<?php echo e($flashDeal->ends_at->toISOString()); ?>')">
                    <span>Ends in:</span>
                    <span style="background:rgba(0,0,0,.25);padding:2px 6px;border-radius:4px;font-family:monospace" x-text="hours"></span>
                    <span style="color:rgba(255,255,255,.7)">:</span>
                    <span style="background:rgba(0,0,0,.25);padding:2px 6px;border-radius:4px;font-family:monospace" x-text="minutes"></span>
                    <span style="color:rgba(255,255,255,.7)">:</span>
                    <span style="background:rgba(0,0,0,.25);padding:2px 6px;border-radius:4px;font-family:monospace" x-text="seconds"></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <a href="<?php echo e(route('shop.index', ['flash_sale' => 1])); ?>" style="color:#fff;font-size:12px;font-weight:600;text-decoration:none">See all →</a>
        </div>
        <div class="scrollbar-hide" style="display:flex;gap:10px;overflow-x:auto;padding:0 16px 16px">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $flashSale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('shop.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured->count() > 0): ?>
<div style="padding:8px 16px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h2 style="font-size:15px;font-weight:900;color:#1f2937;margin:0">⭐ Featured Products</h2>
        <a href="<?php echo e(route('shop.index', ['featured' => 1])); ?>" style="font-size:12px;font-weight:600;color:var(--teal);text-decoration:none">View all →</a>
    </div>
    <div class="products-grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $featured->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('shop.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($newArrivals->count() > 0): ?>
<div style="padding:8px 16px 24px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h2 style="font-size:15px;font-weight:900;color:#1f2937;margin:0">🆕 New Arrivals</h2>
        <a href="<?php echo e(route('shop.index')); ?>" style="font-size:12px;font-weight:600;color:var(--teal);text-decoration:none">View all →</a>
    </div>
    <div class="products-grid">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $newArrivals->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('shop.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function heroSlider(count) {
    return {
        current: 0, count,
        init() { if (count > 1) setInterval(() => this.next(), 5000); },
        next() { this.current = (this.current + 1) % this.count; },
        prev() { this.current = (this.current - 1 + this.count) % this.count; },
    };
}
function countdown(endTime) {
    return {
        hours:'00', minutes:'00', seconds:'00',
        init() {
            const tick = () => {
                const d = new Date(endTime) - new Date();
                if (d <= 0) { this.hours = this.minutes = this.seconds = '00'; return; }
                this.hours   = String(Math.floor(d/3600000)).padStart(2,'0');
                this.minutes = String(Math.floor((d%3600000)/60000)).padStart(2,'0');
                this.seconds = String(Math.floor((d%60000)/1000)).padStart(2,'0');
            };
            tick(); setInterval(tick, 1000);
        }
    };
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/home/index.blade.php ENDPATH**/ ?>