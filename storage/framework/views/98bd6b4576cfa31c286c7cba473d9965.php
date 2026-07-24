<?php $__env->startSection('title', 'Landing Pages'); ?>
<?php $__env->startSection('page-title', 'Landing Pages'); ?>
<?php $__env->startSection('breadcrumb', 'Landing Pages'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-5">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <form method="GET" class="flex gap-2 flex-wrap">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-input w-56" placeholder="Search headline or slug…">
            <select name="status" class="form-select w-36">
                <option value="">All Status</option>
                <option value="draft" <?php if(request('status')==='draft'): echo 'selected'; endif; ?>>Draft</option>
                <option value="published" <?php if(request('status')==='published'): echo 'selected'; endif; ?>>Published</option>
            </select>
            <button type="submit" class="btn-primary btn-sm">Filter</button>
            <a href="<?php echo e(route('admin.landing-pages.index')); ?>" class="btn-outline btn-sm">Reset</a>
        </form>
        <a href="<?php echo e(route('admin.landing-pages.create')); ?>" class="btn-primary btn-sm">
            <i class="fas fa-plus mr-1.5"></i>New Landing Page
        </a>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Headline</th>
                        <th>Product</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Orders</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <p class="font-semibold text-sm text-gray-800"><?php echo e(Str::limit($page->headline, 40)); ?></p>
                            <p class="text-xs text-gray-400">Updated <?php echo e($page->updated_at?->diffForHumans() ?? '—'); ?></p>
                        </td>
                        <td>
                            <span class="text-sm text-gray-600"><?php echo e($page->product->name ?? '— deleted —'); ?></span>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page->status === 'published'): ?>
                            <a href="<?php echo e(url($page->slug)); ?>" target="_blank" class="text-xs text-teal-600 hover:underline">
                                /<?php echo e($page->slug); ?> <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">/<?php echo e($page->slug); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($page->status === 'published'): ?>
                            <span class="text-xs font-semibold text-green-600">✅ Published</span>
                            <?php else: ?>
                            <span class="text-xs font-semibold text-yellow-600">⏳ Draft</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="text-sm text-gray-600"><?php echo e(number_format($page->views)); ?></td>
                        <td class="text-sm font-semibold text-gray-800"><?php echo e($orderCounts[$page->id] ?? 0); ?></td>
                        <td>
                            <div class="flex gap-1.5 flex-wrap">
                                <a href="<?php echo e(route('admin.landing-pages.edit', $page)); ?>" class="btn-secondary btn-sm text-xs">
                                    <i class="fas fa-edit text-[10px]"></i> Edit
                                </a>
                                <form method="POST" action="<?php echo e(route('admin.landing-pages.duplicate', $page)); ?>" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn-outline btn-sm text-xs">
                                        <i class="fas fa-copy text-[10px]"></i> Duplicate
                                    </button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.landing-pages.destroy', $page)); ?>" class="inline"
                                    onsubmit="return confirm('Delete this landing page? This cannot be undone.')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-danger btn-sm text-xs">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-12 text-gray-400">
                            No landing pages yet — <a href="<?php echo e(route('admin.landing-pages.create')); ?>" class="text-teal-600 font-semibold">create your first one</a>.
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4"><?php echo e($pages->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/landing-pages/index.blade.php ENDPATH**/ ?>