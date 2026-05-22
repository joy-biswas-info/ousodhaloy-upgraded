<?php $__env->startSection('page-title', 'Customer: ' . $user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5 text-center">
            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center text-teal-700 font-black text-2xl mx-auto mb-3">
                <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

            </div>
            <h2 class="font-bold text-gray-800 text-lg"><?php echo e($user->name); ?></h2>
            <p class="text-sm text-gray-500"><?php echo e($user->email ?? $user->phone); ?></p>
            <div class="flex justify-center gap-2 mt-2">
                <span class="text-xs font-semibold px-2 py-1 rounded-full <?php echo e($user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
                    <?php echo e($user->is_active ? 'Active' : 'Suspended'); ?>

                </span>
                <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2 py-1 rounded-full capitalize"><?php echo e($user->role); ?></span>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Contact Info</h3>
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone text-teal-500 w-4 text-center"></i>
                    <?php echo e($user->phone ?? '—'); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->phone_verified_at): ?><i class="fas fa-check-circle text-green-500 text-xs ml-1"></i><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-envelope text-teal-500 w-4 text-center"></i>
                    <?php echo e($user->email ?? '—'); ?>

                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar text-teal-500 w-4 text-center"></i>
                    Joined <?php echo e($user->created_at->format('d M Y')); ?>

                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->referral_code): ?>
                <div class="flex items-center gap-2">
                    <i class="fas fa-tag text-teal-500 w-4 text-center"></i>
                    Ref: <span class="font-mono font-bold"><?php echo e($user->referral_code); ?></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Loyalty Points</h3>
            <p class="text-3xl font-black text-teal-700"><?php echo e(number_format($user->total_loyalty_points)); ?></p>
            <p class="text-xs text-gray-400 mt-0.5">≈ ৳<?php echo e(number_format($user->total_loyalty_points / 10, 2)); ?> value</p>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Actions</h3>
            <form method="POST" action="<?php echo e(route('admin.users.update', $user)); ?>" class="space-y-2">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <select name="role" class="form-select text-sm">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['customer','manager','admin']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role); ?>" <?php if($user->role === $role): echo 'selected'; endif; ?>><?php echo e(ucfirst($role)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?php echo e($user->is_active ? 'checked' : ''); ?> class="accent-teal-600">
                    <span class="text-sm text-gray-700">Account Active</span>
                </label>
                <button type="submit" class="btn-primary w-full btn-sm">Update Account</button>
            </form>
        </div>
    </div>

    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-5 py-4 border-b font-bold text-gray-800">
                Order History (<?php echo e($user->orders->count()); ?>)
            </div>
            <table class="admin-table">
                <thead>
                    <tr><th>Order</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $user->orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="font-mono text-xs font-bold text-teal-700"><?php echo e($order->order_number); ?></td>
                        <td class="text-xs text-gray-500"><?php echo e($order->items->count()); ?></td>
                        <td class="font-bold text-sm">৳<?php echo e(number_format($order->total, 0)); ?></td>
                        <td>
                            <span class="text-xs font-semibold <?php echo e($order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600'); ?>">
                                <?php echo e(ucfirst($order->payment_status)); ?>

                            </span>
                        </td>
                        <td><span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span></td>
                        <td class="text-xs text-gray-400"><?php echo e($order->created_at->format('d M Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn-outline btn-sm">View</a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center py-8 text-gray-400 text-sm">No orders yet</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/users/show.blade.php ENDPATH**/ ?>