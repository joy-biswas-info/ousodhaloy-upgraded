<?php $__env->startSection('page-title', 'SMS Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4">

    <div class="grid grid-cols-3 gap-3">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            ['label' => 'Total Sent',  'value' => \App\Models\SmsLog::where('status','sent')->count(),   'color' => 'green'],
            ['label' => 'Failed',      'value' => \App\Models\SmsLog::where('status','failed')->count(),  'color' => 'red'],
            ['label' => 'Queued',      'value' => \App\Models\SmsLog::where('status','queued')->count(),  'color' => 'yellow'],
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
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-semibold capitalize">
                                <?php echo e(str_replace('_',' ',$log->purpose ?? 'general')); ?>

                            </span>
                        </td>
                        <td>
                            <span class="text-xs font-semibold <?php echo e($log->status === 'sent' ? 'text-green-600' : ($log->status === 'failed' ? 'text-red-600' : 'text-yellow-600')); ?>">
                                <?php echo e(ucfirst($log->status)); ?>

                            </span>
                        </td>
                        <td>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($log->order_id): ?>
                            <a href="<?php echo e(route('admin.orders.show', $log->order_id)); ?>" class="text-xs text-teal-700 hover:underline font-mono">
                                #<?php echo e($log->order_id); ?>

                            </a>
                            <?php else: ?> <span class="text-xs text-gray-400">—</span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td class="text-xs text-gray-400 whitespace-nowrap"><?php echo e($log->created_at->format('d M Y h:i A')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">No SMS logs yet</td></tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="p-4"><?php echo e($logs->links()); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/sms-logs.blade.php ENDPATH**/ ?>