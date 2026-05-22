<?php $__env->startSection('page-title', 'Banners'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="px-5 py-4 border-b font-bold text-gray-800">All Banners (<?php echo e($banners->total()); ?>)</div>
                <div class="divide-y">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="p-4 flex items-start gap-4">
                            <div class="w-24 h-14 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center text-white font-bold text-sm"
                                style="background: <?php echo e($banner->bg_color); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->image_url): ?>
                                    <img src="<?php echo e($banner->image_url); ?>" class="w-full h-full object-cover" alt="">
                                <?php else: ?>
                                    <span class="text-xs opacity-75">No Image</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800"><?php echo e($banner->title); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->subtitle): ?>
                                        <p class="text-xs text-gray-500 truncate"><?php echo e($banner->subtitle); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-semibold uppercase"><?php echo e($banner->position); ?></span>
                                            <span
                                                class="text-[10px] font-semibold <?php echo e($banner->is_active ? 'text-green-600' : 'text-gray-400'); ?>"><?php echo e($banner->is_active ? 'Active' : 'Hidden'); ?></span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->link_url): ?><span class="text-[10px] text-blue-500 truncate max-w-xs">→
                                            <?php echo e($banner->link_url); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('admin.settings.banners.destroy', $banner)); ?>"
                                        onsubmit="return confirm('Delete banner?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-danger btn-sm text-xs flex-shrink-0">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="p-12 text-center text-gray-400">No banners yet. Add one →</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="p-4"><?php echo e($banners->links()); ?></div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Banner</h2>
            <form method="POST" action="<?php echo e(route('admin.settings.banners.store')); ?>" enctype="multipart/form-data"
                class="space-y-3">
                <?php echo csrf_field(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><p><?php echo e($e); ?></p><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div>
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-input" placeholder="Banner headline" required>
                </div>
                <div>
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Supporting text">
                </div>
                <div>
                    <label class="form-label">Image *</label>
                    <input type="file" name="image" accept="image/*" class="form-input py-1.5" required>
                    <p class="text-xs text-gray-400 mt-1">Recommended: 1200×400px for hero banners</p>
                </div>
                <div>
                    <label class="form-label">Position *</label>
                    <select name="position" class="form-select" required>
                        <option value="hero">Hero (Main Slider)</option>
                        <option value="promo">Promo (Mid-page)</option>
                        <option value="sidebar">Sidebar</option>
                        <option value="popup">Popup</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Link URL</label>
                    <input type="text" name="link_url" class="form-input" placeholder="/shop?category=medicine">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" class="form-input" placeholder="Shop Now" value="Shop Now">
                    </div>
                    <div>
                        <label class="form-label">Badge Text</label>
                        <input type="text" name="badge_text" class="form-input" placeholder="⚡ Flash Sale">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="form-label">Background Color</label>
                        <input type="color" name="bg_color" value="#0e7673" class="form-input h-10 p-1 cursor-pointer">
                    </div>
                    <div>
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" value="0" class="form-input">
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="accent-teal-600">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <button type="submit" class="btn-primary w-full">Upload Banner</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/settings/banners.blade.php ENDPATH**/ ?>