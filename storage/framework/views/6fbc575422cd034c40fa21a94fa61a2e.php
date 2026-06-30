<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,viewport-fit=cover">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta name="theme-color" content="<?php echo e(\App\Models\Setting::get('brand_primary','#0e7673')); ?>">
<title><?php echo $__env->yieldContent('title', config('app.name','Ousodhaloy')); ?> – Bangladesh's Trusted Online Pharmacy</title>
<meta name="description" content="<?php echo $__env->yieldContent('meta_description','Buy genuine medicine, healthcare and wellness products online. Fast delivery across Bangladesh.'); ?>">
<link rel="icon" type="image/svg+xml" href="/favicon.svg">


<script>
window.tailwind=window.tailwind||{};
tailwind.config={theme:{extend:{colors:{teal:{50:'#f0fafa',100:'#e6f4f4',200:'#c4e8e8',300:'#93d5d5',400:'#5bbebe',500:'#35a5a5',600:'#13a09c',700:'#0e7673',800:'#0a5250',900:'#073f3d'}}}}}
</script>




<?php
$bp   = \App\Models\Setting::get('brand_primary','#0e7673');
$bd   = \App\Models\Setting::get('brand_dark','#0a5250');
$bl   = \App\Models\Setting::get('brand_light','#13a09c');
$bbg  = \App\Models\Setting::get('brand_bg','#e6f4f4');
$msng = \App\Models\Setting::get('messenger_url','');
?>

<style>

/* ── CATEGORY SUBNAV ──────────────────────────────────────── */
.subnav{
  background:#fff;border-bottom:1px solid var(--border);
  position:sticky;top:62px;z-index:190;
}
.subnav-inner{
  max-width:1400px;margin:0 auto;padding:0 16px;
  display:flex;gap:2px;overflow-x:auto;scrollbar-width:none;
  height:44px;align-items:center;
}
.subnav-inner::-webkit-scrollbar{display:none}
.snav-item{
  display:flex;align-items:center;gap:5px;
  padding:5px 11px;border-radius:7px;
  font-size:12px;font-weight:500;color:var(--text-muted);
  white-space:nowrap;cursor:pointer;
  transition:all .15s;text-decoration:none;flex-shrink:0;
}
.snav-item:hover,.snav-item.active{
  background:var(--teal-bg);color:var(--teal);
}

</style>
<?php echo $__env->yieldPushContent('styles'); ?>
<?php echo $__env->make('partials.meta-pixel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>
<body style="background:var(--bg);color:var(--text);font-family:'Segoe UI',system-ui,sans-serif;min-height:100vh">


<div class="subnav">
  <div class="subnav-inner">
    <a href="<?php echo e(route('shop.index')); ?>"
      class="snav-item <?php echo e(request()->routeIs('shop.index')&&!request()->has('category')?'active':''); ?>">
      🏠 All
    </a>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Category::active()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <a href="<?php echo e(route('shop.index',['category'=>$cat->slug])); ?>"
      class="snav-item <?php echo e(request('category')===$cat->slug?'active':''); ?>">
      <?php echo e($cat->icon); ?> <?php echo e($cat->name); ?>

    </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</div>
</body>
</html><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/layouts/checkout.blade.php ENDPATH**/ ?>