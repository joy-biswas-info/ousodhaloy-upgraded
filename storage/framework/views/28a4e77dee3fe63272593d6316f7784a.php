<?php $__env->startSection('title', 'My Account'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">👤 My Account</h1>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
        
        <div class="space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                ['route' => 'account.profile',  'icon' => 'user-cog',  'label' => 'Profile'],
                ['route' => 'account.orders',   'icon' => 'box',       'label' => 'My Orders'],
                ['route' => 'account.wishlist', 'icon' => 'heart',     'label' => 'Wishlist'],
                ['route' => 'account.addresses','icon' => 'map-marker','label' => 'Addresses'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route($link['route'])); ?>"
                class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors <?php echo e(request()->routeIs($link['route']) ? 'bg-teal-700 text-white' : 'bg-white border text-gray-600 hover:bg-teal-50 hover:text-teal-700'); ?>">
                <i class="fas fa-<?php echo e($link['icon']); ?> w-4 text-center"></i>
                <?php echo e($link['label']); ?>

            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <form action="<?php echo e(route('auth.logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium text-red-600 bg-white border hover:bg-red-50 transition-colors">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                </button>
            </form>
        </div>

        
        <div class="sm:col-span-3 space-y-4">
            <div class="bg-white rounded-2xl border p-5">
                <div class="flex items-center gap-4 mb-5 pb-4 border-b">
                    <div class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center text-teal-700 font-black text-2xl">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div>
                        <h2 class="font-bold text-gray-800 text-lg"><?php echo e(auth()->user()->name); ?></h2>
                        <p class="text-sm text-gray-500">Member since <?php echo e(auth()->user()->created_at->format('M Y')); ?></p>
                        <div class="flex items-center gap-1 mt-1">
                            <i class="fas fa-coins text-yellow-500 text-xs"></i>
                            <span class="text-xs font-semibold text-gray-600"><?php echo e(number_format(auth()->user()->total_loyalty_points)); ?> Loyalty Points</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="#" class="space-y-4">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="<?php echo e(auth()->user()->name); ?>" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Phone</label>
                            <input type="text" value="<?php echo e(auth()->user()->phone); ?>" class="form-input bg-gray-50" readonly>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->phone_verified_at): ?>
                            <p class="text-xs text-green-600 mt-1"><i class="fas fa-check-circle mr-1"></i>Verified</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" value="<?php echo e(auth()->user()->email); ?>" class="form-input" placeholder="Optional">
                        </div>
                    </div>
                    <button type="submit" class="btn-primary btn-sm">Update Profile</button>
                </form>
            </div>

            
            <div class="grid grid-cols-3 gap-3">
                <?php $user = auth()->user(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    ['label' => 'Total Orders',   'value' => $user->orders()->count(),                                  'icon' => 'box',     'color' => 'teal'],
                    ['label' => 'Delivered',       'value' => $user->orders()->where('status','delivered')->count(),     'icon' => 'check',   'color' => 'green'],
                    ['label' => 'Loyalty Points',  'value' => number_format($user->total_loyalty_points),               'icon' => 'star',    'color' => 'yellow'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl border p-4 text-center">
                    <i class="fas fa-<?php echo e($s['icon']); ?> text-<?php echo e($s['color']); ?>-500 text-xl mb-1"></i>
                    <p class="font-black text-gray-800 text-lg"><?php echo e($s['value']); ?></p>
                    <p class="text-xs text-gray-500"><?php echo e($s['label']); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/account/profile.blade.php ENDPATH**/ ?>