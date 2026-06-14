<?php $__env->startSection('page-title', 'SMS Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    
    <div class="grid grid-cols-4 gap-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['label' => 'Total',  'value' => $stats->total,  'color' => 'blue'],
            ['label' => 'Sent',   'value' => $stats->sent,   'color' => 'green'],
            ['label' => 'Failed', 'value' => $stats->failed, 'color' => 'red'],
            ['label' => 'Queued', 'value' => $stats->queued, 'color' => 'yellow'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-<?php echo e($card['color']); ?>-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-sms text-<?php echo e($card['color']); ?>-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500"><?php echo e($card['label']); ?></p>
                <p class="text-xl font-black text-gray-800"><?php echo e(number_format($card['value'])); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="bg-white rounded-xl border p-4">
        <form method="GET" class="flex gap-3 flex-wrap items-center">
            <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['sent' => 'Sent', 'failed' => 'Failed', 'queued' => 'Queued']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php echo e(request('status') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <select name="purpose" class="form-select text-sm" onchange="this.form.submit()">
                <option value="">All Types</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['order_placed' => 'Order Placed', 'status_update' => 'Status Update', 'otp' => 'OTP', 'low_stock' => 'Low Stock']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php echo e(request('purpose') === $val ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['status', 'purpose'])): ?>
            <a href="<?php echo e(route('admin.sms-logs')); ?>" class="btn-outline text-sm">Clear filters</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </form>
    </div>

    
    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="font-mono text-sm"><?php echo e($log->phone); ?></td>
                        <td class="max-w-xs">
                            <p class="text-xs text-gray-600 line-clamp-2"><?php echo e($log->message); ?></p>
                        </td>
                        <td>
                            <?php
                                $purposeLabels = [
                                    'order_placed'  => ['Order Placed',   'blue'],
                                    'status_update' => ['Status Update',  'purple'],
                                    'otp'           => ['OTP',            'orange'],
                                    'low_stock'     => ['Low Stock',      'red'],
                                    'general'       => ['General',        'gray'],
                                ];
                                [$purposeLabel, $purposeColor] = $purposeLabels[$log->purpose] ?? [ucfirst(str_replace('_', ' ', $log->purpose ?? 'general')), 'gray'];
                            ?>
                            <span class="text-xs bg-<?php echo e($purposeColor); ?>-100 text-<?php echo e($purposeColor); ?>-700 px-2 py-0.5 rounded font-semibold">
                                <?php echo e($purposeLabel); ?>

                            </span>
                        </td>
                        <td>
                            <?php
                                $statusConfig = [
                                    'sent'   => ['Sent',   'text-green-600',  'fa-check-circle'],
                                    'failed' => ['Failed', 'text-red-600',    'fa-times-circle'],
                                    'queued' => ['Queued', 'text-yellow-600', 'fa-clock'],
                                ];
                                [$statusLabel, $statusColor, $statusIcon] = $statusConfig[$log->status] ?? [ucfirst($log->status), 'text-gray-500', 'fa-circle'];
                            ?>
                            <span class="text-xs font-semibold <?php echo e($statusColor); ?> flex items-center gap-1">
                                <i class="fas <?php echo e($statusIcon); ?>"></i> <?php echo e($statusLabel); ?>

                            </span>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->order_id): ?>
                            <a href="<?php echo e(route('admin.orders.show', $log->order_id)); ?>" class="text-xs text-teal-700 hover:underline font-mono">
                                #<?php echo e($log->order_id); ?>

                            </a>
                            <?php else: ?>
                            <span class="text-xs text-gray-400">—</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="text-xs text-gray-400 whitespace-nowrap"><?php echo e($log->created_at->format('d M Y h:i A')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">
                            <i class="fas fa-sms text-3xl mb-2 block"></i>
                            No SMS logs <?php echo e(request()->hasAny(['status','purpose']) ? 'matching filters' : 'yet'); ?>

                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t flex items-center justify-between">
            <p class="text-xs text-gray-400">
                Showing <?php echo e($logs->firstItem() ?? 0); ?>–<?php echo e($logs->lastItem() ?? 0); ?> of <?php echo e($logs->total()); ?> logs
            </p>
            <?php echo e($logs->withQueryString()->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/sms-logs.blade.php ENDPATH**/ ?>