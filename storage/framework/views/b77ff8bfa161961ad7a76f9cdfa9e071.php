<?php $__env->startSection('page-title', 'Categories'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 gap-5">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <h2 class="font-bold text-gray-800">All Categories</h2>
                    <span class="text-xs text-gray-400"><?php echo e($categories->count()); ?> total</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Icon</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Products</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr x-data="{ editing: false }">
                                <td class="text-2xl"><?php echo e($cat->icon); ?></td>
                                <td>
                                    <span x-show="!editing"
                                        class="font-semibold text-sm text-gray-800"><?php echo e($cat->name); ?></span>
                                    <form x-show="editing" method="POST"
                                        action="<?php echo e(route('admin.categories.update', $cat)); ?>" class="flex gap-2">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                        <input type="text" name="name" value="<?php echo e($cat->name); ?>" class="form-input"
                                            style="width:140px">
                                        <input type="text" name="icon" value="<?php echo e($cat->icon); ?>" class="form-input"
                                            style="width:50px" maxlength="5">
                                        <input type="number" name="sort_order" value="<?php echo e($cat->sort_order); ?>"
                                            class="form-input" style="width:60px">
                                        <input type="hidden" name="is_active"
                                            value="<?php echo e($cat->is_active ? 'true' : 'false'); ?>">
                                        <button type="submit" class="btn-primary btn-sm">Save</button>
                                    </form>
                                </td>
                                <td class="text-xs text-gray-400 font-mono"><?php echo e($cat->slug); ?></td>
                                <td class="text-sm text-gray-600"><?php echo e($cat->products_count); ?></td>
                                <td class="text-sm text-gray-500"><?php echo e($cat->sort_order); ?></td>
                                <td>
                                    <span
                                        class="text-xs font-semibold <?php echo e($cat->is_active ? 'text-green-600' : 'text-gray-400'); ?>">
                                        <?php echo e($cat->is_active ? 'Active' : 'Hidden'); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="flex gap-1.5">
                                        <button @click="editing = !editing" class="btn-secondary btn-sm">
                                            <span x-text="editing ? 'Cancel' : 'Edit'"></span>
                                        </button>
                                        <a href="<?php echo e(route('shop.index', ['category' => $cat->slug])); ?>" target="_blank"
                                            class="btn-outline btn-sm">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <form method="POST" action="<?php echo e(route('admin.categories.destroy', $cat)); ?>"
                                            onsubmit="return confirm('Delete \'<?php echo e($cat->name); ?>\'? This cannot be undone.')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="btn-sm text-red-400 hover:text-red-600 hover:bg-red-50 border border-red-200 rounded-lg px-2 py-1 transition-colors"
                                                <?php echo e($cat->products_count > 0 ? 'disabled title=Has products' : ''); ?>>
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-10 text-gray-400">No categories found</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border p-2">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Category</h2>
            <form method="POST" action="<?php echo e(route('admin.categories.store')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p><?php echo e($e); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div>
                    <label class="form-label">Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Baby Care" required>
                </div>
                <div>
                    <label class="form-label">Icon (emoji)</label>
                    <input type="text" name="icon" class="form-input" placeholder="👶" maxlength="5" value="💊">
                </div>
                <div>
                    <label class="form-label">Slug (auto-generated)</label>
                    <input type="text" name="slug" class="form-input" placeholder="baby-care">
                </div>
                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="2" class="form-input resize-none" placeholder="Optional description"></textarea>
                </div>
                <div>
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" value="0" class="form-input">
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="accent-teal-600">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <button type="submit" class="btn-primary w-full">Add Category</button>
            </form>

            <div class="mt-5 pt-5 border-t">
                <p class="text-xs text-gray-500 mb-2 font-semibold">Common BD Pharmacy Categories:</p>
                <div class="flex flex-wrap gap-1.5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['💊 Medicine', '🧪 Vitamins', '👶 Baby & Mother', '🩸 Diabetes', '❤️ Heart', '🧴 Skin Care', '👁️ Eye & Ear', '🦷 Dental', '💪 Fitness', '🌿 Herbal', '🩺 Devices', '🛁 Personal Care']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="text-xs bg-gray-100 px-2 py-1 rounded cursor-pointer hover:bg-teal-50"
                            onclick="document.querySelector('[name=name]').value='<?php echo e(explode(' ', trim($c), 2)[1] ?? $c); ?>'; document.querySelector('[name=icon]').value='<?php echo e(substr(trim($c), 0, 2)); ?>'"><?php echo e($c); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/categories/index.blade.php ENDPATH**/ ?>