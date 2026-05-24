<div x-show="userMenu" @click.away="userMenu=false" x-cloak
    style="position:absolute;right:0;top:calc(100% + 6px);background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.18);border:1px solid #e5e7eb;width:200px;z-index:600;overflow:hidden;padding:4px 0">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
        <div style="padding:10px 14px 8px;font-size:12px;color:#6b7280;border-bottom:1px solid #f3f4f6">
            <p style="font-weight:700;color:#1f2937;margin:0"><?php echo e(auth()->user()->name); ?></p>
            <p style="margin:0"><?php echo e(auth()->user()->phone ?? auth()->user()->email); ?></p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isManager()): ?>
            <a href="<?php echo e(route('admin.dashboard')); ?>"
                style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                <i class="fas fa-tachometer-alt" style="color:var(--teal);width:14px"></i> Admin Panel
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [['account.orders', 'fa-box', 'My Orders'], ['account.profile', 'fa-user-cog', 'Account'], ['account.wishlist', 'fa-heart', 'Wishlist']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$rt, $ic, $lb]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($rt)); ?>"
                style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                <i class="fas <?php echo e($ic); ?>" style="color:var(--teal);width:14px"></i> <?php echo e($lb); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div style="border-top:1px solid #f3f4f6;margin-top:2px">
            <form action="<?php echo e(route('auth.logout')); ?>" method="POST"><?php echo csrf_field(); ?>
                <button type="submit"
                    style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#dc2626;background:none;border:none;cursor:pointer;font-family:inherit">
                    <i class="fas fa-sign-out-alt" style="width:14px"></i> Logout
                </button>
            </form>
        </div>
    <?php else: ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [['auth.login', 'fa-sign-in-alt', 'Login'], ['auth.register', 'fa-user-plus', 'Register'], ['auth.otp', 'fa-mobile-alt', 'Login with OTP']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$rt, $ic, $lb]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($rt)); ?>"
                style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                <i class="fas <?php echo e($ic); ?>" style="color:var(--teal);width:14px"></i> <?php echo e($lb); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/partials/account-dropdown.blade.php ENDPATH**/ ?>