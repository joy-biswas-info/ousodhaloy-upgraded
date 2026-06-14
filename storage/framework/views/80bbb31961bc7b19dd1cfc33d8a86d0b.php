<?php use App\Models\Order; ?>

<?php $__env->startSection('title', 'Order #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-3xl mx-auto px-4 py-6">

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-5 text-center">
                <div class="text-4xl mb-2">🎉</div>
                <h2 class="font-black text-green-800 text-xl mb-1">Order Placed Successfully!</h2>
                <p class="text-green-700 text-sm"><?php echo e(session('success')); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="bg-white rounded-2xl border p-5 mb-4">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="font-black text-xl text-gray-800">#<?php echo e($order->order_number); ?></h1>
                    <p class="text-xs text-gray-500 mt-0.5">Placed <?php echo e($order->created_at->format('d M Y, h:i A')); ?></p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="status-badge status-<?php echo e($order->status); ?>"><?php echo e($order->status_label); ?></span>
                    <span
                        class="status-badge <?php echo e($order->payment_status === 'paid' ? 'status-delivered' : ($order->payment_status === 'failed' ? 'status-cancelled' : 'status-pending')); ?>">
                        💳 <?php echo e(ucfirst($order->payment_status)); ?>

                    </span>
                </div>
            </div>

            
            <?php
                $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                $currentStep = array_search($order->status, $steps);
                if ($currentStep === false)
                    $currentStep = -1;
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!in_array($order->status, ['cancelled', 'refunded', 'returned'])): ?>
                <div class="mt-5">
                    <div class="flex items-center">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex flex-col items-center <?php echo e($i < count($steps) - 1 ? 'flex-1' : ''); ?>">
                                <div
                                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                                    <?php echo e($currentStep >= $i ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-gray-200 text-gray-400'); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentStep > $i): ?> <i class="fas fa-check text-[10px]"></i>
                                    <?php else: ?> <?php echo e($i + 1); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <span
                                    class="text-[9px] text-center mt-1 font-medium <?php echo e($currentStep >= $i ? 'text-teal-700' : 'text-gray-400'); ?>"
                                    style="max-width:50px">
                                    <?php echo e(ucfirst($step)); ?>

                                </span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i < count($steps) - 1): ?>
                                <div class="flex-1 h-0.5 mx-1 rounded <?php echo e($currentStep > $i ? 'bg-teal-600' : 'bg-gray-200'); ?>"></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            
            <div class="bg-white rounded-2xl border p-5 sm:col-span-2">
                <h3 class="font-bold text-gray-800 mb-3">Order Items</h3>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-3 pb-3 border-b last:border-0 last:pb-0">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-lg border flex-shrink-0 flex items-center justify-center text-xl">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product?->thumbnail): ?>
                                    <img src="<?php echo e($item->product->thumbnail_url); ?>" class="max-h-full max-w-full object-contain">
                                <?php else: ?> 💊 <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-gray-800"><?php echo e($item->product_name); ?></p>
                                <p class="text-xs text-gray-500">৳<?php echo e(number_format($item->price, 2)); ?> × <?php echo e($item->quantity); ?>

                                </p>
                            </div>
                            <span class="font-bold text-sm text-teal-700">৳<?php echo e(number_format($item->subtotal, 2)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="mt-4 pt-3 border-t space-y-1.5 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span><span>৳<?php echo e(number_format($order->subtotal, 2)); ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Delivery</span>
                        <span><?php echo e($order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE'); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount > 0): ?>
                        <div class="flex justify-between text-green-600">
                            <span>Discount (<?php echo e($order->promo_code); ?>)</span>
                            <span>−৳<?php echo e(number_format($order->discount, 2)); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex justify-between font-black text-base border-t pt-2">
                        <span>Total</span>
                        <span class="text-teal-700">৳<?php echo e(number_format($order->total, 2)); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl border p-5">
                <h3 class="font-bold text-gray-800 mb-3">Delivery Address</h3>
                <div class="text-sm text-gray-600 space-y-0.5">
                    <p class="font-semibold text-gray-800"><?php echo e($order->shipping_name); ?></p>
                    <p><i class="fas fa-phone text-xs text-teal-500 mr-1"></i><?php echo e($order->shipping_phone); ?></p>
                    <p><?php echo e($order->shipping_address); ?></p>
                    <p><?php echo e($order->shipping_upazila); ?>, <?php echo e($order->shipping_district); ?></p>
                    <p><?php echo e($order->shipping_division); ?></p>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->pathao_tracking_code): ?>
                    <div class="mt-3 bg-teal-50 rounded-lg p-2.5 text-xs">
                        <p class="font-semibold text-teal-700">Tracking: <?php echo e($order->pathao_tracking_code); ?></p>
                        <p class="text-teal-600">Pathao: <?php echo e($order->pathao_status ?? 'Processing'); ?></p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="bg-white rounded-2xl border p-5">
                <h3 class="font-bold text-gray-800 mb-3">Payment</h3>
                <div class="text-sm text-gray-600 space-y-1.5">
                    <div class="flex justify-between">
                        <span>Method</span>
                        <span class="font-semibold capitalize"><?php echo e(str_replace('_', ' ', $order->payment_method)); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status</span>
                        <span
                            class="font-semibold capitalize <?php echo e($order->payment_status === 'paid' ? 'text-green-600' : ''); ?>"><?php echo e(ucfirst($order->payment_status)); ?></span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->ssl_transaction_id): ?>
                        <div class="flex justify-between">
                            <span>Txn ID</span>
                            <span class="font-mono text-xs"><?php echo e($order->ssl_transaction_id); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border p-5">
            <h3 class="font-bold text-gray-800 mb-4">Order Timeline</h3>
            <div class="space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-teal-500 flex-shrink-0 mt-1"></div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                            <div class="w-0.5 flex-1 bg-gray-200 my-1"></div> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="pb-3">
                            <p class="text-sm font-semibold text-gray-800">
                                <?php echo e(Order::STATUS_LABELS[$h->status] ?? ucfirst($h->status)); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->note): ?>
                            <p class="text-xs text-gray-500"><?php echo e($h->note); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p class="text-xs text-gray-400 mt-0.5"><?php echo e($h->created_at->format('d M Y, h:i A')); ?></p>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="flex flex-wrap gap-3 mt-4">
            <a href="<?php echo e(route('track')); ?>" class="btn-secondary btn-sm">
                <i class="fas fa-search-location mr-1"></i>Track Order
            </a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(route('account.orders')); ?>" class="btn-outline btn-sm">
                    <i class="fas fa-list mr-1"></i>My Orders
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('home')); ?>" class="btn-outline btn-sm">
                <i class="fas fa-home mr-1"></i>Continue Shopping
            </a>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
<?php if(session('success') && (str_contains(session('success'), 'Order placed') || str_contains(session('success'), 'Payment successful'))): ?>
            <?php $pixelPurchase = \App\Models\Setting::get('meta_pixel_purchase', 'true') === 'true'; ?>
            <?php if($pixelPurchase): ?>
                document.addEventListener('DOMContentLoaded', () => {
                    if (window.fbTrack) {
                        window.fbTrack('Purchase', {
                            content_ids: <?php echo json_encode($order->items->pluck('product_id')->toArray()); ?>,
                            content_type: 'product',
                            value: <?php echo e($order->total); ?>,
                            currency: 'BDT',
                            num_items: <?php echo e($order->items->sum('quantity')); ?>,
                            order_id: '<?php echo e($order->order_number); ?>'
                        });
                    }
                });
            <?php endif; ?>
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/shop/orders/show.blade.php ENDPATH**/ ?>