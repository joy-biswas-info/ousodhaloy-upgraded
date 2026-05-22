<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice <?php echo e($order->order_number); ?></title>
<style>
body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1f2937; margin: 0; padding: 20px; }
.header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #0e7673; }
.brand-name { font-size: 22px; font-weight: 900; color: #0e7673; }
.brand-sub { font-size: 10px; color: #6b7280; margin-top: 2px; }
.invoice-meta { text-align: right; }
.invoice-meta h2 { font-size: 16px; font-weight: 700; color: #0e7673; margin: 0 0 4px; }
.invoice-meta p { margin: 2px 0; font-size: 11px; color: #6b7280; }
.grid-2 { display: table; width: 100%; margin-bottom: 20px; }
.col { display: table-cell; width: 50%; vertical-align: top; padding-right: 16px; }
.col:last-child { padding-right: 0; padding-left: 16px; }
.section-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #9ca3af; margin-bottom: 6px; }
.info-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px; }
.info-box p { margin: 3px 0; font-size: 11px; color: #374151; }
.info-box .name { font-weight: 700; color: #1f2937; font-size: 12px; }
table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
th { background: #0e7673; color: white; padding: 8px 10px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; }
td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
tr:nth-child(even) td { background: #f9fafb; }
.totals { width: 260px; margin-left: auto; }
.totals table { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
.totals td { padding: 7px 12px; }
.total-row td { background: #0e7673 !important; color: white; font-weight: 900; font-size: 13px; }
.badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }
.badge-paid { background: #d1fae5; color: #065f46; }
.badge-unpaid { background: #fef3c7; color: #92400e; }
.footer { margin-top: 30px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center; font-size: 10px; color: #9ca3af; }
</style>
</head>
<body>

<div class="header">
    <div>
        <div class="brand-name">Ousodhaloy</div>
        <div class="brand-sub"><?php echo e(\App\Models\Setting::get('site_address', 'Dhaka, Bangladesh')); ?></div>
        <div class="brand-sub"><?php echo e(\App\Models\Setting::get('site_phone', '09610016778')); ?> · <?php echo e(\App\Models\Setting::get('site_email', 'info@ousodhaloy.com')); ?></div>
        <div class="brand-sub">DGDA Licensed · 100% Genuine Products</div>
    </div>
    <div class="invoice-meta">
        <h2>TAX INVOICE</h2>
        <p>#<?php echo e($order->order_number); ?></p>
        <p>Date: <?php echo e($order->created_at->format('d M Y')); ?></p>
        <p>
            <span class="badge <?php echo e($order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid'); ?>">
                <?php echo e(ucfirst($order->payment_status)); ?>

            </span>
        </p>
    </div>
</div>

<div class="grid-2">
    <div class="col">
        <div class="section-title">Bill To</div>
        <div class="info-box">
            <p class="name"><?php echo e($order->shipping_name); ?></p>
            <p>Phone: <?php echo e($order->shipping_phone); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->user?->email): ?><p>Email: <?php echo e($order->user->email); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <div class="col">
        <div class="section-title">Ship To</div>
        <div class="info-box">
            <p class="name"><?php echo e($order->shipping_name); ?></p>
            <p><?php echo e($order->shipping_address); ?></p>
            <p><?php echo e($order->shipping_upazila); ?>, <?php echo e($order->shipping_district); ?></p>
            <p><?php echo e($order->shipping_division); ?>, Bangladesh</p>
        </div>
    </div>
</div>

<div class="section-title">Items</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Product</th>
            <th>SKU</th>
            <th style="text-align:right">Unit Price</th>
            <th style="text-align:center">Qty</th>
            <th style="text-align:right">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($i+1); ?></td>
            <td><strong><?php echo e($item->product_name); ?></strong></td>
            <td style="color:#9ca3af"><?php echo e($item->product_sku ?? '—'); ?></td>
            <td style="text-align:right">৳<?php echo e(number_format($item->price, 2)); ?></td>
            <td style="text-align:center"><?php echo e($item->quantity); ?></td>
            <td style="text-align:right;font-weight:700">৳<?php echo e(number_format($item->subtotal, 2)); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </tbody>
</table>

<div class="totals">
    <table>
        <tr><td>Subtotal</td><td style="text-align:right">৳<?php echo e(number_format($order->subtotal, 2)); ?></td></tr>
        <tr><td>Delivery Charge</td><td style="text-align:right"><?php echo e($order->delivery_charge > 0 ? '৳'.number_format($order->delivery_charge, 2) : 'FREE'); ?></td></tr>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount > 0): ?>
        <tr><td>Discount (<?php echo e($order->promo_code); ?>)</td><td style="text-align:right;color:#065f46">−৳<?php echo e(number_format($order->discount, 2)); ?></td></tr>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <tr class="total-row"><td><strong>Grand Total</strong></td><td style="text-align:right"><strong>৳<?php echo e(number_format($order->total, 2)); ?></strong></td></tr>
    </table>
</div>

<div style="margin-top:20px">
    <div class="section-title">Payment Details</div>
    <p style="font-size:11px">Method: <?php echo e(ucwords(str_replace('_',' ',$order->payment_method))); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->ssl_transaction_id): ?> · Txn: <?php echo e($order->ssl_transaction_id); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </p>
</div>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->customer_note): ?>
<div style="margin-top:12px">
    <div class="section-title">Customer Note</div>
    <p style="font-size:11px"><?php echo e($order->customer_note); ?></p>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<div class="footer">
    <p>Thank you for shopping at Ousodhaloy — Bangladesh's Trusted Online Pharmacy</p>
    <p>This is a computer-generated invoice and does not require a signature.</p>
    <p>For support: <?php echo e(\App\Models\Setting::get('site_phone')); ?> · <?php echo e(\App\Models\Setting::get('site_email')); ?></p>
</div>
</body>
</html>
<?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/orders/invoice.blade.php ENDPATH**/ ?>