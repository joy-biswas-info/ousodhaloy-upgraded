<?php $__env->startSection('page-title', 'Products'); ?>
<?php $__env->startSection('content'); ?>
    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-input w-56"
                    placeholder="Search products...">
                <select name="status" class="form-select w-36">
                    <option value="">All Status</option>
                    <option value="active" <?php if(request('status') == 'active'): echo 'selected'; endif; ?>>Active</option>
                    <option value="inactive" <?php if(request('status') == 'inactive'): echo 'selected'; endif; ?>>Inactive</option>
                    <option value="featured" <?php if(request('status') == 'featured'): echo 'selected'; endif; ?>>Featured</option>
                    <option value="flash_sale" <?php if(request('status') == 'flash_sale'): echo 'selected'; endif; ?>>Flash Sale</option>
                    <option value="low_stock" <?php if(request('status') == 'low_stock'): echo 'selected'; endif; ?>>Low Stock</option>
                    <option value="out_of_stock" <?php if(request('status') == 'out_of_stock'): echo 'selected'; endif; ?>>Out of Stock</option>
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
                <a href="<?php echo e(route('admin.products.index')); ?>" class="btn-outline btn-sm">Reset</a>
            </form>
            <a href="<?php echo e(route('admin.products.trash')); ?>" class="btn-outline mr-2 text-red-500">
                <i class="fas fa-trash-alt mr-1"></i> Trash
            </a>
            <a href="<?php echo e(route('admin.products.bulk')); ?>" class="btn-outline mr-2">
                <i class="fas fa-file-import mr-1"></i> Bulk Import
            </a>
            <a href="<?php echo e(route('admin.products.create')); ?>" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> Add Product
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Sales</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gray-50 rounded-lg border flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->thumbnail): ?>
                                                <img src="<?php echo e($p->thumbnail_url); ?>" class="max-h-full max-w-full object-contain">
                                            <?php else: ?> <span class="text-lg">💊</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm text-gray-800"><?php echo e(Str::limit($p->name, 40)); ?></p>
                                            <p class="text-xs text-gray-400"><?php echo e($p->generic_name); ?> · <?php echo e($p->brand?->name); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs text-gray-500"><?php echo e($p->category?->name ?? '—'); ?></td>
                                <td>
                                    <p class="font-bold text-sm text-teal-700">৳<?php echo e(number_format($p->effective_price, 2)); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->mrp && $p->mrp > $p->effective_price): ?>
                                        <p class="text-xs text-gray-400 line-through">৳<?php echo e(number_format($p->mrp, 2)); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td>
                                    <span
                                        class="<?php echo e($p->stock == 0 ? 'text-red-600 font-bold' : ($p->stock <= $p->low_stock_alert ? 'text-orange-600 font-bold' : 'text-gray-700')); ?>">
                                        <?php echo e($p->stock); ?> <?php echo e($p->unit); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full font-semibold <?php echo e($p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'); ?>"><?php echo e($p->is_active ? 'Active' : 'Hidden'); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->is_featured): ?> <span
                                            class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-semibold">★
                                        Featured</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->is_flash_sale): ?> <span
                                            class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-semibold">⚡
                                        Sale</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->requires_prescription): ?> <span
                                            class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">Rx</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </td>
                                <td class="text-sm text-gray-600"><?php echo e(number_format($p->total_sold)); ?></td>
                                <td>
                                    <div class="flex gap-1.5 flex-wrap">
                                        <a href="<?php echo e(route('admin.products.edit', $p)); ?>" class="btn-secondary btn-sm">Edit</a>
                                        <a href="<?php echo e(route('shop.product', $p->slug)); ?>" target="_blank"
                                            class="btn-outline btn-sm"><i class="fas fa-external-link-alt"></i></a>
                                        <form method="POST" action="<?php echo e(route('admin.products.destroy', $p)); ?>"
                                            onsubmit="return confirm('Move to trash?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn-danger btn-sm" title="Move to Trash"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">No products found</td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php echo e($products->withQueryString()->links()); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/products/index.blade.php ENDPATH**/ ?>