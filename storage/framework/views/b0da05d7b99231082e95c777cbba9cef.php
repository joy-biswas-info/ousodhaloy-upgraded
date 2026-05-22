<?php $__env->startSection('title', 'My Addresses'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">🗺️ My Addresses</h1>

    <?php $addresses = auth()->user()->addresses; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-2xl border p-5 <?php echo e($addr->is_default ? 'border-teal-400 ring-2 ring-teal-100' : ''); ?>">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-gray-800"><?php echo e($addr->label); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($addr->is_default): ?>
                    <span class="text-xs bg-teal-100 text-teal-700 font-semibold px-2 py-0.5 rounded-full">Default</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="text-sm text-gray-600 space-y-0.5">
                <p class="font-semibold text-gray-800"><?php echo e($addr->name); ?></p>
                <p><i class="fas fa-phone text-xs text-teal-500 mr-1"></i><?php echo e($addr->phone); ?></p>
                <p><?php echo e($addr->address); ?></p>
                <p><?php echo e($addr->upazila); ?>, <?php echo e($addr->district); ?></p>
                <p><?php echo e($addr->division); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="sm:col-span-2 bg-white rounded-2xl border p-10 text-center text-gray-400">
            <div class="text-5xl mb-3">📍</div>
            <p class="font-semibold">No saved addresses</p>
            <p class="text-sm mt-1">Save an address when you checkout</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/account/addresses.blade.php ENDPATH**/ ?>