<?php $__env->startSection('page-title', 'Order #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-5">
        
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn-outline btn-sm"><i
                        class="fas fa-arrow-left mr-1"></i>Back</a>
                <span class="status-badge status-<?php echo e($order->status); ?> text-sm"><?php echo e($order->status_label); ?></span>
                <span class="text-xs text-gray-400"><?php echo e($order->created_at->format('d M Y, h:i A')); ?></span>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="<?php echo e(route('admin.orders.invoice', $order)); ?>" class="btn-outline btn-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i>Invoice
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->pathao_consignment_id || $order->steadfast_consignment_id): ?>
                    <a href="<?php echo e(route('admin.orders.shipping-label', $order)); ?>" class="btn-outline btn-sm" target="_blank">
                        <i class="fas fa-tag mr-1"></i>Print Label
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$order->pathao_consignment_id && !$order->steadfast_consignment_id): ?>
                    <button type="button" onclick="openPathaoModal()" class="btn-secondary btn-sm">🚚 Pathao</button>
                <?php elseif($order->pathao_consignment_id): ?>
                    <form action="<?php echo e(route('admin.orders.sync-pathao', $order)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn-outline btn-sm">↻ Sync Pathao</button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($steadfastEnabled): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$order->steadfast_consignment_id && !$order->pathao_consignment_id): ?>
                        <form action="<?php echo e(route('admin.orders.steadfast', $order)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-secondary btn-sm"
                                onclick="return confirm('Push to Steadfast courier?')">📦 Steadfast</button>
                        </form>
                    <?php elseif($order->steadfast_consignment_id): ?>
                        <form action="<?php echo e(route('admin.orders.sync-steadfast', $order)); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn-outline btn-sm">↻ Sync Steadfast</button>
                        </form>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            
            <div class="lg:col-span-2 space-y-5">

                
                <div class="bg-white rounded-xl border overflow-hidden">
                    <div class="px-5 py-4 border-b font-bold text-gray-800">Order Items</div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <p class="font-semibold text-sm"><?php echo e($item->product_name); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product_sku): ?>
                                        <p class="text-xs text-gray-400">SKU: <?php echo e($item->product_sku); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </td>
                                    <td class="text-sm">৳<?php echo e(number_format($item->price, 2)); ?></td>
                                    <td class="text-sm font-bold"><?php echo e($item->quantity); ?></td>
                                    <td class="font-bold text-teal-700">৳<?php echo e(number_format($item->subtotal, 2)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="text-right text-sm text-gray-600 px-4 py-2">Subtotal</td>
                                <td class="font-bold px-4 py-2">৳<?php echo e(number_format($order->subtotal, 2)); ?></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right text-sm text-gray-600 px-4 py-2">Delivery</td>
                                <td class="font-bold px-4 py-2">
                                    <?php echo e($order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE'); ?>

                                </td>
                            </tr>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount > 0): ?>
                                <tr>
                                    <td colspan="3" class="text-right text-sm text-green-600 px-4 py-2">Discount
                                        (<?php echo e($order->promo_code); ?>)</td>
                                    <td class="font-bold text-green-600 px-4 py-2">−৳<?php echo e(number_format($order->discount, 2)); ?>

                                    </td>
                                </tr>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <tr class="text-base">
                                <td colspan="3" class="text-right font-black px-4 py-2.5">Total</td>
                                <td class="font-black text-teal-700 px-4 py-2.5">৳<?php echo e(number_format($order->total, 2)); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Update Status</h3>
                    <form method="POST" action="<?php echo e(route('admin.orders.status', $order)); ?>" class="space-y-3">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">New Status</label>
                                <select name="status" class="form-select">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Order::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php if($order->status === $key): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Note (optional)</label>
                                <input type="text" name="note" class="form-input" placeholder="Internal or customer note">
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="notify_customer" value="1" checked id="notify"
                                class="accent-teal-600">
                            <label for="notify" class="text-sm text-gray-600 cursor-pointer">Send SMS to customer</label>
                        </div>
                        <button type="submit" class="btn-primary btn-sm">Update Status</button>
                    </form>
                    <div class="border-t mt-4 pt-4">
                        <form method="POST" action="<?php echo e(route('admin.orders.payment', $order)); ?>"
                            class="flex items-center gap-3">
                            <?php echo csrf_field(); ?>
                            <label class="form-label mb-0">Payment Status</label>
                            <select name="payment_status" class="form-select w-auto">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['unpaid', 'pending', 'paid', 'failed', 'refunded']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($s); ?>" <?php if($order->payment_status === $s): echo 'selected'; endif; ?>><?php echo e(ucfirst($s)); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <button type="submit" class="btn-secondary btn-sm">Update</button>
                        </form>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-3">Admin Note</h3>
                    <form method="POST" action="<?php echo e(route('admin.orders.note', $order)); ?>" class="flex gap-3">
                        <?php echo csrf_field(); ?>
                        <textarea name="admin_note" rows="2" class="form-input flex-1 resize-none"
                            placeholder="Private note (not visible to customer)"><?php echo e($order->admin_note); ?></textarea>
                        <button type="submit" class="btn-secondary btn-sm self-start">Save</button>
                    </form>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Status Timeline</h3>
                    <div class="space-y-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->statusHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $h): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-teal-500 mt-1 flex-shrink-0"></div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                                    <div class="w-0.5 flex-1 bg-gray-200 mt-1"></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="pb-3">
                                    <p class="text-sm font-semibold text-gray-800">
                                        <?php echo e(\App\Models\Order::STATUS_LABELS[$h->status] ?? ucfirst($h->status)); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($h->note): ?>
                                    <p class="text-xs text-gray-500"><?php echo e($h->note); ?></p><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <p class="text-xs text-gray-400 mt-0.5"><?php echo e($h->created_at->format('d M Y h:i A')); ?> ·
                                        <?php echo e($h->changed_by); ?></p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="space-y-4">

                
                <div class="bg-white rounded-xl border p-4">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Customer</h3>
                    <p class="font-semibold text-sm text-gray-800"><?php echo e($order->customer_name); ?></p>
                    <p class="text-xs text-gray-500 flex items-center gap-1 mt-1"><i
                            class="fas fa-phone text-teal-500 w-3"></i><?php echo e($order->customer_phone); ?></p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->user): ?>
                        <p class="text-xs text-gray-500 flex items-center gap-1 mt-1"><i
                                class="fas fa-envelope text-teal-500 w-3"></i><?php echo e($order->user->email); ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?php echo e($order->user->orders()->count()); ?> total orders</p>
                    <?php else: ?>
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded mt-1 inline-block">Guest</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="bg-white rounded-xl border p-4">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Delivery Address</h3>
                    <div class="text-xs text-gray-600 space-y-0.5">
                        <p class="font-semibold text-gray-800"><?php echo e($order->shipping_name); ?></p>
                        <p><?php echo e($order->shipping_phone); ?></p>
                        <p><?php echo e($order->shipping_address); ?></p>
                        <p><?php echo e($order->shipping_upazila); ?>, <?php echo e($order->shipping_district); ?></p>
                        <p><?php echo e($order->shipping_division); ?> <?php echo e($order->shipping_postcode); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->customer_note): ?>
                        <div class="mt-2 bg-yellow-50 rounded p-2 text-xs text-yellow-800">📝 <?php echo e($order->customer_note); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <div class="bg-white rounded-xl border p-4">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Payment</h3>
                    <div class="text-xs space-y-1.5 text-gray-600">
                        <div class="flex justify-between"><span>Method</span><span
                                class="font-semibold capitalize"><?php echo e(str_replace('_', ' ', $order->payment_method)); ?></span>
                        </div>
                        <div class="flex justify-between"><span>Status</span><span
                                class="font-semibold capitalize <?php echo e($order->payment_status === 'paid' ? 'text-green-600' : ''); ?>"><?php echo e(ucfirst($order->payment_status)); ?></span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->ssl_transaction_id): ?>
                            <div class="flex justify-between"><span>Txn</span><span
                        class="font-mono text-xs"><?php echo e($order->ssl_transaction_id); ?></span></div><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->pathao_consignment_id): ?>
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-teal-800 text-sm">🚚 Pathao</h3>
                            <a href="<?php echo e(route('admin.orders.shipping-label', $order)); ?>" target="_blank"
                                class="text-xs bg-teal-600 text-white px-2 py-1 rounded-lg">
                                <i class="fas fa-print mr-1"></i>Label
                            </a>
                        </div>
                        <div class="text-xs text-teal-700 space-y-1">
                            <p>Consignment: <span class="font-mono font-bold"><?php echo e($order->pathao_consignment_id); ?></span></p>
                            <p>Tracking: <span class="font-mono font-bold"><?php echo e($order->pathao_tracking_code); ?></span></p>
                            <p>Status: <span class="font-bold"><?php echo e($order->pathao_status ?? 'Pending'); ?></span></p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->steadfast_consignment_id): ?>
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-indigo-800 text-sm">📦 Steadfast</h3>
                            <a href="<?php echo e(route('admin.orders.shipping-label', $order)); ?>" target="_blank"
                                class="text-xs bg-indigo-600 text-white px-2 py-1 rounded-lg">
                                <i class="fas fa-print mr-1"></i>Label
                            </a>
                        </div>
                        <div class="text-xs text-indigo-700 space-y-1">
                            <p>Consignment: <span class="font-mono font-bold"><?php echo e($order->steadfast_consignment_id); ?></span></p>
                            <p>Tracking: <span class="font-mono font-bold"><?php echo e($order->steadfast_tracking_code); ?></span></p>
                            <p>Status: <span class="font-bold"><?php echo e($order->steadfast_status ?? 'Pending'); ?></span></p>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->prescription_image): ?>
                    <div class="bg-white rounded-xl border p-4">
                        <h3 class="font-bold text-gray-800 text-sm mb-3">Prescription</h3>
                        <a href="<?php echo e(asset('storage/' . $order->prescription_image)); ?>" target="_blank">
                            <img src="<?php echo e(asset('storage/' . $order->prescription_image)); ?>" class="w-full rounded-lg border"
                                alt="Prescription">
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$order->pathao_consignment_id && !$order->steadfast_consignment_id): ?>
    <div id="pathao-modal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 text-lg">🚚 Push to Pathao</h3>
                <button onclick="closePathaoModal()"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <form action="<?php echo e(route('admin.orders.pathao', $order)); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div class="bg-gray-50 rounded-xl p-3 text-xs text-gray-600 space-y-1">
                    <p><b>Order:</b> <?php echo e($order->order_number); ?></p>
                    <p><b>Recipient:</b> <?php echo e($order->shipping_name); ?> · <?php echo e($order->shipping_phone); ?></p>
                    <p><b>Address:</b> <?php echo e($order->shipping_district); ?>, <?php echo e($order->shipping_division); ?></p>
                    <p><b>COD:</b>
                        <?php echo e($order->payment_status !== 'paid' ? '৳' . number_format($order->total, 2) : 'Prepaid — no collection'); ?>

                    </p>
                </div>
                <div>
                    <label class="form-label">Recipient City *</label>
                    <select name="pathao_city_id" id="pathao-city" class="form-select"
                        onchange="loadPathaoZones(this.value)" required>
                        <option value="">Loading cities…</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Recipient Zone *</label>
                    <select name="pathao_zone_id" id="pathao-zone" class="form-select"
                        onchange="loadPathaoAreas(this.value)" required disabled>
                        <option value="">Select city first</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Recipient Area <span
                            class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <select name="pathao_area_id" id="pathao-area" class="form-select" disabled>
                        <option value="">Select zone first</option>
                    </select>
                </div>
                <p class="text-xs text-blue-700 bg-blue-50 border border-blue-200 rounded-lg p-2">
                    💡 Save default city/zone in <a href="<?php echo e(route('admin.settings.index')); ?>"
                        class="underline font-semibold">Settings → Pathao</a> to pre-select automatically.
                </p>
                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closePathaoModal()" class="btn-outline flex-1">Cancel</button>
                    <button type="submit" class="btn-primary flex-1"><i class="fas fa-truck mr-1"></i>Push Order</button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // ← PHP data safely serialised to JS
        const pathaoDefaults = <?php echo json_encode($pathaoDefaults ?? [], 15, 512) ?>;
        function openPathaoModal() {
            const m = document.getElementById('pathao-modal');
            if (!m) return;
            m.classList.remove('hidden');
            m.classList.add('flex');
            loadPathaoCities();
        }

        function closePathaoModal() {
            const m = document.getElementById('pathao-modal');
            if (!m) return;
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        async function pathaoLookup(type, params = {}) {
            const qs = new URLSearchParams({ type, ...params });
            const res = await fetch(`/admin/orders/pathao-lookup?${qs}`);
            const json = await res.json();
            if (!json.success) throw new Error(json.error || 'Lookup failed');
            return json.data || [];
        }

        async function loadPathaoCities() {
            const sel = document.getElementById('pathao-city');
            sel.innerHTML = '<option value="">Loading cities…</option>';
            sel.disabled = true;

            try {
                const cities = await pathaoLookup('cities');
                sel.innerHTML = '<option value="">— Select city —</option>';
                let defaultSelected = false;
                cities.forEach(c => {
                    const o = document.createElement('option');
                    o.value = c.city_id;
                    o.textContent = c.city_name;
                    if (String(c.city_id) === String(pathaoDefaults.city)) {
                        o.selected = true;
                        defaultSelected = true;
                    }
                    sel.appendChild(o);
                });
                sel.disabled = false;

                // Auto-load zones if a default city is set
                if (defaultSelected && pathaoDefaults.city) {
                    await loadPathaoZones(pathaoDefaults.city);
                }
            } catch (e) {
                sel.innerHTML = `<option value="">⚠ ${e.message}</option>`;
                sel.disabled = false;
            }
        }

        async function loadPathaoZones(cityId) {
            if (!cityId) return;
            const sel = document.getElementById('pathao-zone');
            sel.disabled = true;
            sel.innerHTML = '<option value="">Loading zones…</option>';

            try {
                const zones = await pathaoLookup('zones', { city_id: cityId });
                sel.innerHTML = '<option value="">— Select zone —</option>';
                let defaultSelected = false;
                zones.forEach(z => {
                    const o = document.createElement('option');
                    o.value = z.zone_id;
                    o.textContent = z.zone_name;
                    if (String(z.zone_id) === String(pathaoDefaults.zone)) {
                        o.selected = true;
                        defaultSelected = true;
                    }
                    sel.appendChild(o);
                });
                sel.disabled = false;

                // Auto-load areas if a default zone is set
                if (defaultSelected && pathaoDefaults.zone) {
                    await loadPathaoAreas(pathaoDefaults.zone);
                }
            } catch (e) {
                sel.innerHTML = `<option value="">⚠ ${e.message}</option>`;
                sel.disabled = false;
            }
        }

        async function loadPathaoAreas(zoneId) {
            if (!zoneId) return;
            const sel = document.getElementById('pathao-area');
            sel.disabled = true;
            sel.innerHTML = '<option value="">Loading areas…</option>';

            try {
                const areas = await pathaoLookup('areas', { zone_id: zoneId });
                sel.innerHTML = '<option value="">(Optional) Select area</option>';
                areas.forEach(a => {
                    const o = document.createElement('option');
                    o.value = a.area_id;
                    o.textContent = a.area_name;
                    if (String(a.area_id) === String(pathaoDefaults.area)) {
                        o.selected = true;
                    }
                    sel.appendChild(o);
                });
                sel.disabled = false;
            } catch (e) {
                sel.innerHTML = '<option value="">(Area lookup unavailable)</option>';
                sel.disabled = false;
            }
        }

        document.getElementById('pathao-modal')?.addEventListener('click', closePathaoModal);
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/orders/show.blade.php ENDPATH**/ ?>