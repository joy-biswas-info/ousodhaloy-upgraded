<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .card {
            background: #fff;
            border-radius: 12px;
            max-width: 560px;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .header {
            background: #0f766e;
            padding: 24px;
            text-align: center;
        }

        .header h1 {
            color: #fff;
            margin: 0;
            font-size: 20px;
        }

        .header p {
            color: rgba(255, 255, 255, 0.8);
            margin: 4px 0 0;
            font-size: 13px;
        }

        .body {
            padding: 24px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        .row:last-child {
            border-bottom: none;
        }

        .label {
            color: #666;
        }

        .value {
            font-weight: 600;
            color: #111;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 600;
            background: #fef3c7;
            color: #92400e;
        }

        .items {
            background: #f9fafb;
            border-radius: 8px;
            padding: 12px 16px;
            margin: 16px 0;
        }

        .item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
        }

        .item:last-child {
            border-bottom: none;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0 0;
            font-size: 15px;
            font-weight: 700;
            color: #0f766e;
        }

        .btn {
            display: block;
            text-align: center;
            background: #0f766e;
            color: #fff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            margin: 20px 0 0;
        }

        .footer {
            text-align: center;
            padding: 16px;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="header">
            <h1>🛍️ New Order Received</h1>
            <p><?php echo e(now()->format('d M Y, h:i A')); ?></p>
        </div>
        <div class="body">
            <div class="row">
                <span class="label">Order Number</span>
                <span class="value">#<?php echo e($order->order_number); ?></span>
            </div>
            <div class="row">
                <span class="label">Customer</span>
                <span class="value"><?php echo e($order->shipping_name); ?></span>
            </div>
            <div class="row">
                <span class="label">Phone</span>
                <span class="value"><?php echo e($order->shipping_phone); ?></span>
            </div>
            <div class="row">
                <span class="label">Address</span>
                <span class="value"><?php echo e($order->shipping_address); ?>, <?php echo e($order->shipping_district); ?></span>
            </div>
            <div class="row">
                <span class="label">Payment</span>
                <span class="value"><?php echo e(strtoupper(str_replace('_', ' ', $order->payment_method))); ?></span>
            </div>
            <div class="row">
                <span class="label">Status</span>
                <span class="badge"><?php echo e(ucfirst($order->status)); ?></span>
            </div>

            <div class="items">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="item">
                        <span><?php echo e($item->product_name); ?> × <?php echo e($item->quantity); ?></span>
                        <span>৳<?php echo e(number_format($item->subtotal, 2)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount > 0): ?>
                    <div class="item" style="color: #16a34a">
                        <span>Discount (<?php echo e($order->promo_code); ?>)</span>
                        <span>−৳<?php echo e(number_format($order->discount, 2)); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="item">
                    <span style="color:#666">Delivery</span>
                    <span><?php echo e($order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE'); ?></span>
                </div>
                <div class="total-row">
                    <span>Total</span>
                    <span>৳<?php echo e(number_format($order->total, 2)); ?></span>
                </div>
            </div>

            <a href="<?php echo e(config('app.url')); ?>/admin/orders/<?php echo e($order->id); ?>" class="btn">
                View Order in Admin Panel →
            </a>
        </div>
        <div class="footer">Ousodhaloy · This is an automated notification</div>
    </div>
</body>

</html>
<?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/emails/new-order.blade.php ENDPATH**/ ?>