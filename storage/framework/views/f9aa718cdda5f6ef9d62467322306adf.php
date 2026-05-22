<?php $__env->startSection('title', 'Create Account'); ?>
<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
            <div class="bg-gradient-to-br from-teal-900 to-teal-700 p-7 text-center text-white">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-teal-700 font-black text-2xl mx-auto mb-3">ও</div>
                <h1 class="text-2xl font-black">Create Account</h1>
                <p class="text-white/70 text-sm mt-1">Join Ousodhaloy for faster checkout & order tracking</p>
            </div>
            <div class="p-7 space-y-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <p><?php echo e($e); ?></p> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <form method="POST" action="<?php echo e(route('auth.register.post')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-input" placeholder="Your full name" required>
                    </div>
                    <div>
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>" class="form-input" placeholder="01XXXXXXXXX" required>
                        <p class="text-xs text-gray-400 mt-1">Valid Bangladeshi mobile number</p>
                    </div>
                    <div>
                        <label class="form-label">Email Address (optional)</label>
                        <input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-input" placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" placeholder="Min 6 characters" required>
                    </div>
                    <div>
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
                    </div>
                    <button type="submit" class="btn-primary w-full py-3">Create Account</button>
                </form>
                <div class="text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="<?php echo e(route('auth.login')); ?>" class="text-teal-700 font-semibold hover:underline">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/auth/register.blade.php ENDPATH**/ ?>