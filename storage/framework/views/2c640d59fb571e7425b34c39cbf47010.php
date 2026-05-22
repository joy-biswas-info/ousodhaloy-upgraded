<?php $__env->startSection('title', 'Track Your Order'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="text-center mb-8">
        <div class="text-5xl mb-3">📦</div>
        <h1 class="text-2xl font-black text-gray-800">Track Your Order</h1>
        <p class="text-gray-500 text-sm mt-1">Enter your order number and phone to see the latest status</p>
    </div>

    <div class="bg-white rounded-2xl border p-6 mb-6">
        <form method="POST" action="<?php echo e(route('track.search')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <div>
                <label class="form-label">Order Number</label>
                <input type="text" name="order_number" value="<?php echo e(old('order_number', $order?->order_number)); ?>"
                    class="form-input text-center font-mono tracking-wider" placeholder="OUS-240101-XXXX" required>
            </div>
            <div>
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" value="<?php echo e(old('phone')); ?>"
                    class="form-input" placeholder="01XXXXXXXXX" required>
            </div>
            <button type="submit" class="btn-primary w-full py-3">
                <i class="fas fa-search mr-2"></i>Track Order
            </button>
        </form>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center text-red-700">
        <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
        <p class="font-semibold"><?php echo e($error); ?></p>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order): ?>
    <div class="bg-white rounded-2xl border p-5 space-y-5">
        
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-black text-xl text-gray-800">#<?php echo e($order->order_number); ?></h2>
                <p class="text-xs text-gray-500"><?php echo e($order->created_at->format('d M Y, h:i A')); ?></p>
            </div>
            <span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span>
        </div>

        
        <?php
            $steps = [
                ['key' => 'pending',      'label' => 'Order Placed', 'icon' => 'check'],
                ['key' => 'confirmed',    'label' => 'Confirmed',    'icon' => 'clipboard-check'],
                ['key' => 'processing',   'label' => 'Processing',   'icon' => 'cog'],
                ['key' => 'shipped',      'label' => 'Shipped',      'icon' => 'truck'],
                ['key' => 'delivered',    'label' => 'Delivered',    'icon' => 'home'],
            ];
            $statusOrder = ['pending','confirmed','processing','ready_to_ship','shipped','out_for_delivery','delivered'];
            $currentIdx = array_search($order->status, $statusOrder);
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($order->status, ['cancelled','refunded','returned'])): ?>
        <div class="relative">
            <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-200 z-0"></div>
            <div class="flex justify-between relative z-10">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $done = $currentIdx !== false && $currentIdx >= array_search($step['key'], $statusOrder); ?>
                <div class="flex flex-col items-center gap-1.5 flex-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all <?php echo e($done ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-gray-200 text-gray-400'); ?>">
                        <i class="fas fa-<?php echo e($step['icon']); ?> text-xs"></i>
                    </div>
                    <span class="text-[9px] text-center font-medium <?php echo e($done ? 'text-teal-700' : 'text-gray-400'); ?>" style="max-width:52px"><?php echo e($step['label']); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center text-red-700">
            <i class="fas fa-times-circle text-2xl mb-1"></i>
            <p class="font-semibold">Order <?php echo e(ucfirst($order->status)); ?></p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->pathao_tracking_code): ?>
        <div class="bg-teal-50 rounded-xl p-3.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-teal-700">Courier Tracking</p>
                    <p class="text-sm font-mono text-teal-800 font-bold"><?php echo e($order->pathao_tracking_code); ?></p>
                    <p class="text-xs text-teal-600">Pathao: <?php echo e($order->pathao_status ?? 'Processing'); ?></p>
                </div>
                <div class="text-3xl">🚚</div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div>
            <h3 class="font-bold text-gray-800 text-sm mb-2">Items (<?php echo e($order->items->count()); ?>)</h3>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex justify-between text-sm py-1.5 border-b last:border-0">
                <span class="text-gray-700"><?php echo e($item->product_name); ?> × <?php echo e($item->quantity); ?></span>
                <span class="font-semibold text-gray-800">৳<?php echo e(number_format($item->subtotal, 2)); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="flex justify-between font-black text-base pt-2">
                <span>Total Paid</span>
                <span class="text-teal-700">৳<?php echo e(number_format($order->total, 2)); ?></span>
            </div>
        </div>

        
        <div>
            <h3 class="font-bold text-gray-800 text-sm mb-3">Status History</h3>
            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex gap-3 items-start">
                    <div class="flex flex-col items-center pt-1">
                        <div class="w-2.5 h-2.5 rounded-full bg-teal-500 flex-shrink-0"></div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?><div class="w-0.5 h-full min-h-[20px] bg-gray-200 mt-1"></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800"><?php echo e(\App\Models\Order::STATUS_LABELS[$h->status] ?? ucfirst($h->status)); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->note): ?><p class="text-xs text-gray-500"><?php echo e($h->note); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-gray-400"><?php echo e($h->created_at->format('d M Y, h:i A')); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="flex gap-2 flex-wrap">
            <a href="<?php echo e(route('home')); ?>" class="btn-secondary btn-sm">🛒 Continue Shopping</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?> <a href="<?php echo e(route('account.orders')); ?>" class="btn-outline btn-sm">My Orders</a> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/orders/track.blade.php ENDPATH**/ ?>