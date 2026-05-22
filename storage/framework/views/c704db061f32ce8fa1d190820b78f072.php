<?php $__env->startSection('page-title', 'Prescriptions'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">
    <div class="grid grid-cols-3 gap-3 mb-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['pending' => ['color' => 'yellow', 'label' => 'Pending'], 'approved' => ['color' => 'green', 'label' => 'Approved'], 'rejected' => ['color' => 'red', 'label' => 'Rejected']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-<?php echo e($info['color']); ?>-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-medical text-<?php echo e($info['color']); ?>-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium"><?php echo e($info['label']); ?></p>
                <p class="text-xl font-black text-gray-800"><?php echo e($prescriptions->where('status', $s)->count()); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr><th>Image</th><th>Customer</th><th>Order</th><th>Status</th><th>Uploaded</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $prescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr x-data="{ open: false }">
                        <td>
                            <button @click="open = true" class="w-12 h-12 bg-gray-50 border rounded-lg overflow-hidden flex items-center justify-center hover:opacity-80 transition-opacity">
                                <img src="<?php echo e(asset('storage/'.$p->image)); ?>" class="max-h-full max-w-full object-cover" alt="Prescription">
                            </button>
                            
                            <div x-show="open" @click="open = false" x-cloak class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4 cursor-pointer">
                                <img src="<?php echo e(asset('storage/'.$p->image)); ?>" class="max-h-[90vh] max-w-[90vw] rounded-xl shadow-2xl" @click.stop>
                            </div>
                        </td>
                        <td>
                            <p class="font-semibold text-sm"><?php echo e($p->user?->name ?? $p->guest_phone ?? 'Guest'); ?></p>
                            <p class="text-xs text-gray-400"><?php echo e($p->user?->phone ?? $p->guest_phone); ?></p>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->order): ?>
                            <a href="<?php echo e(route('admin.orders.show', $p->order)); ?>" class="text-xs font-mono text-teal-700 hover:underline"><?php echo e($p->order->order_number); ?></a>
                            <?php else: ?> <span class="text-xs text-gray-400">No order</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                <?php echo e($p->status === 'approved' ? 'bg-green-100 text-green-700' : ($p->status === 'rejected' ? 'bg-red-100 text-red-700' : ($p->status === 'reviewed' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700'))); ?>">
                                <?php echo e(ucfirst($p->status)); ?>

                            </span>
                        </td>
                        <td class="text-xs text-gray-400"><?php echo e($p->created_at->format('d M Y')); ?></td>
                        <td>
                            <div class="flex gap-1.5 flex-wrap">
                                <form method="POST" action="<?php echo e(route('admin.prescriptions.review', $p)); ?>" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn-secondary btn-sm text-xs">✅ Approve</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.prescriptions.review', $p)); ?>" class="inline">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" class="btn-danger btn-sm text-xs">❌ Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">No prescriptions uploaded yet</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4"><?php echo e($prescriptions->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/prescriptions/index.blade.php ENDPATH**/ ?>