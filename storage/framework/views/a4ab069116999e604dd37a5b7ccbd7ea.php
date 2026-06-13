<?php $__env->startSection('page-title', 'Brands'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="px-5 py-4 border-b border-teal-100 font-bold text-gray-800 flex justify-between items-center">
                    <span>All Brands (<?php echo e($brands->total()); ?>)</span>
                </div>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Brand</th>
                            <th>Country</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr x-data="{ editing: false }">

        
        <td>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brand->logo): ?>
                        <img src="<?php echo e($brand->logo_url); ?>" class="max-h-full max-w-full object-contain">
                    <?php else: ?>
                        <i class="fas fa-flask text-gray-400 text-xs"></i>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <p class="font-semibold text-sm text-gray-800"><?php echo e($brand->name); ?></p>
                    <p class="text-xs text-gray-400 font-mono"><?php echo e($brand->slug); ?></p>
                </div>
            </div>
        </td>

        
        <td class="text-sm text-gray-500"><?php echo e($brand->country ?? '—'); ?></td>

        
        <td class="text-sm text-gray-600"><?php echo e($brand->products_count); ?></td>

        
        <td>
            <span class="text-xs font-semibold <?php echo e($brand->is_active ? 'text-green-600' : 'text-gray-400'); ?>">
                <?php echo e($brand->is_active ? 'Active' : 'Hidden'); ?>

            </span>
        </td>

        
        <td class="space-y-2">

            
            <div class="flex gap-1.5" x-show="!editing">

                <button @click="editing = true" class="btn-secondary btn-sm">
                    Edit
                </button>

                
                <form method="POST"
                      action="<?php echo e(route('admin.brands.update', $brand)); ?>"
                      x-show="false"
                      x-ref="toggleForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <input type="hidden" name="name" value="<?php echo e($brand->name); ?>">
                    <input type="hidden" name="country" value="<?php echo e($brand->country); ?>">
                    <input type="hidden" name="is_active" value="<?php echo e($brand->is_active ? 0 : 1); ?>">
                </form>

                <button @click="$refs.toggleForm.submit()"
                        class="btn-outline btn-sm text-xs">
                    <?php echo e($brand->is_active ? 'Hide' : 'Show'); ?>

                </button>

                
                <form method="POST"
                      action="<?php echo e(route('admin.brands.destroy', $brand)); ?>"
                      onsubmit="return confirm('Delete this brand? Products will be moved to another brand.');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>

                    <button type="submit" class="btn-danger btn-sm">
                        Delete
                    </button>
                </form>

            </div>

            
            <form x-show="editing"
                  method="POST"
                  action="<?php echo e(route('admin.brands.update', $brand)); ?>"
                  class="flex gap-2">

                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <input type="text" name="name" value="<?php echo e($brand->name); ?>" class="form-input" style="width:140px">
                <input type="text" name="country" value="<?php echo e($brand->country); ?>" class="form-input" style="width:100px">

                <button type="submit" class="btn-primary btn-sm">Save</button>
                <button type="button" @click="editing = false" class="btn-outline btn-sm">Cancel</button>
            </form>

        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="5" class="text-center py-10 text-gray-400">
            No brands found
        </td>
    </tr>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</tbody>
                </table>
                <div class="p-4"><?php echo e($brands->links()); ?></div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Brand</h2>
            <form method="POST" action="<?php echo e(route('admin.brands.store')); ?>" enctype="multipart/form-data"
                class="space-y-3">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label">Brand Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="Square Pharmaceuticals" required>
                </div>
                <div>
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="Bangladesh" class="form-input" placeholder="Bangladesh">
                </div>
                <div>
                    <label class="form-label">Logo (optional)</label>
                    <input type="file" name="logo" accept="image/*" class="form-input py-1.5">
                </div>
                <button type="submit" class="btn-primary w-full">Add Brand</button>
            </form>

            <div class="mt-5 pt-5 border-t">
                <p class="text-xs text-gray-500 mb-2 font-semibold">🇧🇩 Top BD Pharma Companies:</p>
                <div class="space-y-1 text-xs text-gray-500">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Square Pharmaceuticals', 'Beximco Pharma', 'Renata Limited', 'ACI Limited', 'Incepta Pharma', 'Drug International', 'Aristopharma', 'Eskayef', 'Opsonin Pharma', 'Globe Pharma']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p>• <?php echo e($b); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/brands/index.blade.php ENDPATH**/ ?>