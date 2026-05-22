<?php $__env->startSection('page-title', 'Product Trash'); ?>
<?php $__env->startSection('breadcrumb', 'Products / Trash'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500"><?php echo e($products->total()); ?> deleted products — restore or permanently delete.</p>
            <a href="<?php echo e(route('admin.products.index')); ?>" class="btn-outline btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Products
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Deleted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="opacity-75 hover:opacity-100">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->thumbnail): ?>
                                            <img src="<?php echo e($p->thumbnail_url); ?>" class="max-h-full max-w-full object-contain">
                                        <?php else: ?> 💊 <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-700 line-through"><?php echo e($p->name); ?></p>
                                        <p class="text-xs text-gray-400"><?php echo e($p->brand?->name); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-xs text-gray-500"><?php echo e($p->category?->name ?? '—'); ?></td>
                            <td class="text-sm font-semibold text-gray-600">৳<?php echo e(number_format($p->price, 2)); ?></td>
                            <td class="text-xs text-gray-400"><?php echo e($p->deleted_at->diffForHumans()); ?></td>
                            <td>
                                <div class="flex gap-1.5">
                                    <form method="POST" action="<?php echo e(route('admin.products.restore', $p->id)); ?>">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn-secondary btn-sm">
                                            <i class="fas fa-undo mr-1"></i>Restore
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('admin.products.force-delete', $p->id)); ?>"
                                        onsubmit="return confirm('Permanently delete <?php echo e(addslashes($p->name)); ?>? This cannot be undone.')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn-danger btn-sm">
                                            <i class="fas fa-trash mr-1"></i>Delete Forever
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center py-16 text-gray-400">
                                <i class="fas fa-trash-alt text-4xl mb-3 block opacity-30"></i>
                                <p class="font-semibold">Trash is empty</p>
                                <p class="text-xs mt-1">Deleted products appear here</p>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
            <div class="p-4"><?php echo e($products->links()); ?></div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/products/trash.blade.php ENDPATH**/ ?>