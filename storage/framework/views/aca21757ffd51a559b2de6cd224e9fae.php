<?php $__env->startSection('page-title', 'Staff Management'); ?>
<?php $__env->startSection('breadcrumb', 'Staff'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        
        <div class="lg:col-span-2 space-y-4">

            
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="px-5 py-4 border-b flex items-center justify-between">
                    <p class="font-bold text-gray-800">Team Members (<?php echo e($staff->count()); ?>)</p>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="px-5 py-4 border-b last:border-0 flex items-center gap-4" x-data="{ editing: false }">

                        
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                            style="background:var(--teal)">
                            <?php echo e(strtoupper(substr($member->name, 0, 1))); ?>

                        </div>

                        
                        <div class="flex-1 min-w-0" x-show="!editing">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-gray-800 text-sm"><?php echo e($member->name); ?></p>
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full <?php echo e($member->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-teal-100 text-teal-700'); ?>">
                                    <?php echo e(ucfirst($member->role)); ?>

                                </span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$member->is_active): ?>
                                    <span
                                        class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-600">Suspended</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($member->id === auth()->id()): ?>
                                    <span
                                        class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-500">You</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($member->email); ?>

                                <?php echo e($member->phone ? '· ' . $member->phone : ''); ?></p>
                        </div>

                        
                        <form method="POST" action="<?php echo e(route('admin.staff.update', $member)); ?>" x-show="editing" x-cloak
                            class="flex-1 grid grid-cols-2 gap-2">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <input type="text" name="name" value="<?php echo e($member->name); ?>" class="form-input col-span-2"
                                placeholder="Full name" required>
                            <input type="email" name="email" value="<?php echo e($member->email); ?>" class="form-input" placeholder="Email"
                                required>
                            <input type="text" name="phone" value="<?php echo e($member->phone); ?>" class="form-input" placeholder="Phone">
                            <input type="password" name="password" class="form-input"
                                placeholder="New password (leave blank to keep)">
                            <select name="role" class="form-select">
                                <option value="manager" <?php if($member->role === 'manager'): echo 'selected'; endif; ?>>Manager</option>
                                <option value="admin" <?php if($member->role === 'admin'): echo 'selected'; endif; ?>>Admin</option>
                            </select>
                            <label class="flex items-center gap-2 col-span-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" <?php echo e($member->is_active ? 'checked' : ''); ?>

                                    class="accent-teal-600">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                            <div class="col-span-2 flex gap-2">
                                <button type="submit" class="btn-primary btn-sm flex-1">Save</button>
                                <button type="button" @click="editing=false" class="btn-outline btn-sm flex-1">Cancel</button>
                            </div>
                        </form>

                        
                        <div class="flex items-center gap-2 flex-shrink-0" x-show="!editing">
                            <button @click="editing=true" class="btn-outline btn-sm">
                                <i class="fas fa-edit text-xs"></i>
                            </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($member->id !== auth()->id()): ?>
                                <form method="POST" action="<?php echo e(route('admin.staff.destroy', $member)); ?>"
                                    onsubmit="return confirm('Remove <?php echo e($member->name); ?> from staff?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-danger btn-sm">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">
                        No staff members yet. Add one →
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <p class="text-sm font-bold text-gray-700 mb-3">Role Permissions</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 rounded-xl" style="background:var(--teal-bg)">
                        <p class="text-sm font-bold mb-1" style="color:var(--teal)">Manager</p>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li>✓ View & manage orders</li>
                            <li>✓ Update order status</li>
                            <li>✓ Manage products</li>
                            <li>✓ View customers</li>
                            <li>✗ Delete products</li>
                            <li>✗ Manage staff</li>
                            <li>✗ Change settings</li>
                        </ul>
                    </div>
                    <div class="p-3 rounded-xl bg-purple-50">
                        <p class="text-sm font-bold text-purple-700 mb-1">Admin</p>
                        <ul class="text-xs text-gray-600 space-y-1">
                            <li>✓ Everything Manager can do</li>
                            <li>✓ Delete & restore products</li>
                            <li>✓ Manage staff accounts</li>
                            <li>✓ Change all settings</li>
                            <li>✓ View financial reports</li>
                            <li>✓ Push to couriers</li>
                            <li>✓ Full system access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border p-5 h-fit">
            <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Staff Member</h3>
            <form method="POST" action="<?php echo e(route('admin.staff.store')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="e.g. Rahim Ahmed" required
                        value="<?php echo e(old('name')); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>         <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="form-label">Email *</label>
                    <input type="email" name="email" class="form-input" placeholder="staff@ousodhaloy.com" required
                        value="<?php echo e(old('email')); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>              <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-input" placeholder="01XXXXXXXXX" value="<?php echo e(old('phone')); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>      <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div>
                    <label class="form-label">Role *</label>
                    <select name="role" class="form-select" required>
                        <option value="manager" selected>Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-input" placeholder="Min 8 characters" required>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-user-plus mr-2"></i> Add Staff Member
                </button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/staff/index.blade.php ENDPATH**/ ?>