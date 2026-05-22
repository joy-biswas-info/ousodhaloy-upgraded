<?php $__env->startSection('page-title', 'Product Reviews'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['label' => 'Total Reviews',    'value' => \App\Models\ProductReview::count(),                          'color' => 'blue'],
            ['label' => 'Pending Approval', 'value' => \App\Models\ProductReview::where('is_approved',false)->count(), 'color' => 'yellow'],
            ['label' => 'Approved',         'value' => \App\Models\ProductReview::where('is_approved',true)->count(),  'color' => 'green'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-<?php echo e($card['color']); ?>-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-<?php echo e($card['color']); ?>-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium"><?php echo e($card['label']); ?></p>
                <p class="text-xl font-black text-gray-800"><?php echo e($card['value']); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr><th>Product</th><th>Customer</th><th>Rating</th><th>Review</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('shop.product', $review->product->slug)); ?>" target="_blank" class="font-semibold text-sm text-gray-800 hover:text-teal-700">
                                <?php echo e(Str::limit($review->product->name, 30)); ?>

                            </a>
                        </td>
                        <td>
                            <p class="font-semibold text-sm"><?php echo e($review->user->name); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($review->user->phone); ?></p>
                        </td>
                        <td>
                            <div class="flex gap-0.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=1; $i<=5; $i++): ?>
                                <i class="fas fa-star text-xs <?php echo e($i <= $review->rating ? 'text-yellow-400' : 'text-gray-200'); ?>"></i>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <span class="text-xs text-gray-500"><?php echo e($review->rating); ?>/5</span>
                        </td>
                        <td class="max-w-xs">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->title): ?><p class="font-semibold text-sm text-gray-800"><?php echo e($review->title); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->body): ?><p class="text-xs text-gray-500 mt-0.5 line-clamp-2"><?php echo e($review->body); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <span class="text-xs font-semibold <?php echo e($review->is_approved ? 'text-green-600' : 'text-yellow-600'); ?>">
                                <?php echo e($review->is_approved ? '✅ Approved' : '⏳ Pending'); ?>

                            </span>
                        </td>
                        <td class="text-xs text-gray-400"><?php echo e($review->created_at->format('d M Y')); ?></td>
                        <td>
                            <div class="flex gap-1.5">
                                <form method="POST" action="<?php echo e(route('admin.reviews.approve', $review)); ?>" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="<?php echo e($review->is_approved ? 'btn-outline' : 'btn-secondary'); ?> btn-sm text-xs">
                                        <?php echo e($review->is_approved ? 'Hide' : 'Approve'); ?>

                                    </button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" class="inline" onsubmit="return confirm('Delete this review?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-danger btn-sm text-xs">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-12 text-gray-400">No reviews yet</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4"><?php echo e($reviews->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/reviews/index.blade.php ENDPATH**/ ?>