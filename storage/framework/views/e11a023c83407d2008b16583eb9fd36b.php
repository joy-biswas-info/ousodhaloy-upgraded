<?php $__env->startSection('page-title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-5" x-data="{ tab: '<?php echo e(request('tab', 'general')); ?>' }">

        
        <div class="bg-white rounded-xl border p-1.5 flex gap-1 overflow-x-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            'general' => '🌐 General',
            'orders' => '🚚 Delivery',
            'payment' => '💳 Payments',
            'checkout' => '🛒 Checkout',
            'sms' => '📱 SMS',
            'pathao' => '🚚 Pathao',
            'pixel' => '📊 Pixel',
            'steadfast' => '📦 Steadfast',
            'notifications' => '🔔 Notifications',
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button @click="tab = '<?php echo e($key); ?>'"
                    :class="tab === '<?php echo e($key); ?>' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    class="flex-shrink-0 px-3 py-2 rounded-lg text-xs font-semibold transition-colors whitespace-nowrap"><?php echo e($label); ?></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div x-show="tab === 'general'" class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">🌐 General Settings</h2>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" enctype="multipart/form-data"
                class="space-y-4">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="general">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Site Name</label><input type="text" name="site_name"
                            value="<?php echo e($settings['site_name'] ?? ''); ?>" class="form-input"></div>
                    <div><label class="form-label">Tagline</label><input type="text" name="site_tagline"
                            value="<?php echo e($settings['site_tagline'] ?? ''); ?>" class="form-input"></div>
                    <div><label class="form-label">Phone</label><input type="text" name="site_phone"
                            value="<?php echo e($settings['site_phone'] ?? ''); ?>" class="form-input"></div>
                    <div><label class="form-label">Email</label><input type="email" name="site_email"
                            value="<?php echo e($settings['site_email'] ?? ''); ?>" class="form-input"></div>
                    <div class="col-span-2"><label class="form-label">Address</label><input type="text"
                            name="site_address" value="<?php echo e($settings['site_address'] ?? ''); ?>" class="form-input"></div>

                    <div>
                        <label class="form-label">Messenger / WhatsApp URL</label>
                        <input type="url" name="messenger_url" value="<?php echo e($settings['messenger_url'] ?? ''); ?>"
                            class="form-input" placeholder="https://m.me/yourpage or https://wa.me/8801...">
                        <p class="text-xs text-gray-400 mt-1">Shows a chat button on the storefront. Leave blank to hide.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 pt-5">
                        <input type="checkbox" name="maintenance_mode" value="true"
                            <?php echo e(($settings['maintenance_mode'] ?? '') === 'true' ? 'checked' : ''); ?>

                            class="accent-teal-600">
                        <label class="text-sm text-gray-700">Maintenance Mode</label>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Save General Settings</button>
            </form>
        </div>

        
        <div x-show="tab === 'orders'" class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">🚚 Delivery Settings</h2>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-4 mb-6">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="orders">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Default Delivery Charge (৳)</label><input type="number"
                            name="delivery_charge" value="<?php echo e($settings['delivery_charge'] ?? 60); ?>" class="form-input">
                    </div>
                    <div><label class="form-label">Free Delivery Above (৳)</label><input type="number"
                            name="free_delivery_min" value="<?php echo e($settings['free_delivery_min'] ?? 500); ?>"
                            class="form-input"></div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="guest_checkout" value="true"
                        <?php echo e(($settings['guest_checkout'] ?? '') === 'true' ? 'checked' : ''); ?> class="accent-teal-600">
                    <span class="text-sm text-gray-700">Allow Guest Checkout (no account required)</span>
                </label>
                <button type="submit" class="btn-primary">Save Delivery Settings</button>
            </form>

            
            <div class="border-t pt-5">
                <h3 class="font-bold text-gray-800 mb-3">Delivery Zones</h3>
                <div class="overflow-x-auto mb-4">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Zone</th>
                                <th>Division</th>
                                <th>Charge</th>
                                <th>Free Above</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $deliveryZones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr x-data="{ editing: false }">
                                    <td class="font-semibold text-sm"><?php echo e($zone->name); ?></td>
                                    <td class="text-xs text-gray-500"><?php echo e($zone->division); ?></td>
                                    <td>
                                        <span x-show="!editing">৳<?php echo e($zone->delivery_charge); ?></span>
                                        <form x-show="editing" method="POST"
                                            action="<?php echo e(route('admin.settings.delivery-zones.update', $zone)); ?>"
                                            class="flex gap-1 flex-wrap items-center">
                                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                            <input type="hidden" name="name" value="<?php echo e($zone->name); ?>">
                                            <input type="hidden" name="division" value="<?php echo e($zone->division); ?>">
                                            <input type="number" name="delivery_charge"
                                                value="<?php echo e($zone->delivery_charge); ?>" step="0.01"
                                                class="form-input py-1 text-xs" style="width:80px">
                                            <input type="number" name="free_delivery_above"
                                                value="<?php echo e($zone->free_delivery_above); ?>" step="0.01"
                                                class="form-input py-1 text-xs" style="width:80px">
                                            <input type="number" name="estimated_days"
                                                value="<?php echo e($zone->estimated_days); ?>" class="form-input py-1 text-xs"
                                                style="width:55px">
                                            <input type="hidden" name="is_active"
                                                value="<?php echo e($zone->is_active ? '1' : '0'); ?>">
                                            <button type="submit" class="btn-primary btn-sm">Save</button>
                                            <button type="button" @click="editing=false"
                                                class="btn-outline btn-sm">Cancel</button>
                                        </form>
                                    </td>
                                    <td x-show="!editing">৳<?php echo e($zone->free_delivery_above); ?></td>
                                    <td x-show="!editing"><?php echo e($zone->estimated_days); ?>d</td>
                                    <td><span
                                            class="text-xs font-semibold <?php echo e($zone->is_active ? 'text-green-600' : 'text-gray-400'); ?>"><?php echo e($zone->is_active ? 'Active' : 'Off'); ?></span>
                                    </td>
                                    <td x-show="!editing">
                                        <div class="flex gap-1.5">
                                            <button type="button" @click="editing=true"
                                                class="btn-secondary btn-sm">Edit</button>
                                            <form method="POST"
                                                action="<?php echo e(route('admin.settings.delivery-zones.destroy', $zone)); ?>"
                                                onsubmit="return confirm('Delete this zone?')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button class="btn-danger btn-sm">Del</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Add New Zone</h4>
                <form method="POST" action="<?php echo e(route('admin.settings.delivery-zones.store')); ?>"
                    class="grid grid-cols-3 gap-3">
                    <?php echo csrf_field(); ?>
                    <input type="text" name="name" placeholder="Zone name (e.g. Dhaka City)" class="form-input"
                        required>
                    <select name="division" class="form-select" required>
                        <option value="">Division</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('bd.divisions'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($div); ?>"><?php echo e($div); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <input type="number" name="delivery_charge" placeholder="Charge (৳)" class="form-input" required>
                    <input type="number" name="free_delivery_above" placeholder="Free above (৳)" value="500"
                        class="form-input">
                    <input type="number" name="estimated_days" placeholder="Est. days" value="2"
                        class="form-input">
                    <button type="submit" class="btn-secondary">Add Zone</button>
                </form>
            </div>
        </div>

        
        <div x-show="tab === 'payment'" class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">💳 Payment Methods</h2>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-5">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="payment">

                
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-gray-700">Enable / Disable Methods</p>
                    <p class="text-xs text-gray-400 -mt-1">bKash, Nagad, and Rocket are processed through SSL Commerz —
                        enabling SSL Commerz shows all mobile banking options automatically.</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            'cod_enabled' => ['Cash on Delivery (COD)', '💵', 'Always show COD option at checkout'],
            'ssl_enabled' => ['Online Payment via SSL Commerz (Card / bKash / Nagad / Rocket)', '💳', 'Enables card, bKash, Nagad, Rocket, net banking'],
            'bank_enabled' => ['Bank Transfer', '🏦', 'Show bank transfer option at checkout'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$label, $icon, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div
                            class="flex items-center justify-between p-3.5 border rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3">
                                <span class="text-xl mt-0.5"><?php echo e($icon); ?></span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800"><?php echo e($label); ?></p>
                                    <p class="text-xs text-gray-400"><?php echo e($desc); ?></p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 ml-3">
                                <input type="checkbox" name="<?php echo e($key); ?>" value="true"
                                    <?php echo e(($settings[$key] ?? 'true') === 'true' ? 'checked' : ''); ?> class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </label>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="border-t pt-4">
                    <p class="text-sm font-semibold text-gray-700 mb-3">SSL Commerz Credentials</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="form-label">Store ID</label><input type="text" name="ssl_store_id"
                                value="<?php echo e($settings['ssl_store_id'] ?? ''); ?>" class="form-input"></div>
                        <div><label class="form-label">Store Password</label><input type="password" name="ssl_store_pass"
                                value="<?php echo e($settings['ssl_store_pass'] ?? ''); ?>" class="form-input"></div>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer mt-3">
                        <input type="checkbox" name="ssl_is_live" value="true"
                            <?php echo e(($settings['ssl_is_live'] ?? '') === 'true' ? 'checked' : ''); ?> class="accent-teal-600">
                        <span class="text-sm text-gray-700">Live Mode (uncheck for sandbox)</span>
                    </label>
                </div>
                <button type="submit" class="btn-primary">Save Payment Settings</button>
            </form>
        </div>

        
        <div x-show="tab === 'checkout'" class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-1 pb-2 border-b">🛒 Checkout Field Control</h2>
            <p class="text-xs text-gray-500 mb-4">Control which fields appear on the checkout form. Required fields are
                always shown.</p>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-3">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="checkout">
                <?php
                    $fields = [
                        ['key' => 'shipping_email', 'label' => 'Email Address', 'always' => false],
                        ['key' => 'shipping_postcode', 'label' => 'Postal / ZIP Code', 'always' => false],
                        ['key' => 'shipping_upazila', 'label' => 'Upazila / Area', 'always' => false],
                        ['key' => 'prescription', 'label' => 'Prescription Upload', 'always' => false],
                        ['key' => 'customer_note', 'label' => 'Order Notes', 'always' => false],
                        ['key' => 'promo_code', 'label' => 'Promo Code Field', 'always' => false],
                    ];
                    $cf = json_decode($settings['checkout_fields'] ?? '{}', true) ?: [];
                ?>

                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th class="text-center">Visible</th>
                                <th class="text-center">Required</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Full Name', 'Phone Number', 'Division', 'District', 'Full Address']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alwaysField): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="bg-gray-50">
                                    <td class="text-sm font-medium text-gray-700"><?php echo e($alwaysField); ?> <span
                                            class="text-xs text-teal-600">(always required)</span></td>
                                    <td class="text-center"><i class="fas fa-check-circle text-teal-500"></i></td>
                                    <td class="text-center"><i class="fas fa-check-circle text-teal-500"></i></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $visible = $cf[$f['key']]['visible'] ?? true;
                                    $required = $cf[$f['key']]['required'] ?? false;
                                ?>
                                <tr>
                                    <td class="text-sm font-medium text-gray-800"><?php echo e($f['label']); ?></td>
                                    <td class="text-center">
                                        <input type="checkbox" name="checkout_field[<?php echo e($f['key']); ?>][visible]"
                                            value="1" <?php echo e($visible ? 'checked' : ''); ?> class="accent-teal-600">
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="checkout_field[<?php echo e($f['key']); ?>][required]"
                                            value="1" <?php echo e($required ? 'checked' : ''); ?> class="accent-teal-600">
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="checkout_fields" id="checkout-fields-json">
                <button type="submit" onclick="buildCheckoutJson()" class="btn-primary">Save Checkout Settings</button>
            </form>
        </div>

        
        <div x-show="tab === 'sms'" class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">📱 MimSMS Settings</h2>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="sms">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Username</label><input type="text" name="mimsms_username"
                            value="<?php echo e($settings['mimsms_username'] ?? ''); ?>" class="form-input"
                            placeholder="Your MimSMS username"></div>
                    <div><label class="form-label">API Key</label><input type="text" name="mimsms_api_key"
                            value="<?php echo e($settings['mimsms_api_key'] ?? ''); ?>" class="form-input"
                            placeholder="From app.mimsms.com"></div>
                    <div><label class="form-label">Sender ID</label><input type="text" name="mimsms_sender_id"
                            value="<?php echo e($settings['mimsms_sender_id'] ?? 'Ousodhaloy'); ?>" class="form-input"></div>
                </div>
                
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Notification Triggers</p>
                    <div class="space-y-2">
                        <label
                            class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50 cursor-pointer">
                            <div class="flex items-center gap-3">
                                <span class="text-lg">📦</span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">Order Placed</p>
                                    <p class="text-xs text-gray-400">Sent when a customer places a new order</p>
                                </div>
                            </div>
                            <div class="relative flex-shrink-0 ml-4">
                                <input type="checkbox" name="sms_order_confirm" value="true"
                                    <?php echo e(($settings['sms_order_confirm'] ?? '') === 'true' ? 'checked' : ''); ?>

                                    class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                </div>
                            </div>
                        </label>

                        
                        <div class="border rounded-xl overflow-hidden">
                            <label class="flex items-center justify-between p-4 border-b hover:bg-gray-50 cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <span class="text-lg">🔄</span>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">Status Updates</p>
                                        <p class="text-xs text-gray-400">Master toggle — enable/disable all status SMS</p>
                                    </div>
                                </div>
                                <div class="relative flex-shrink-0 ml-4">
                                    <input type="checkbox" name="sms_status_update" value="true"
                                        <?php echo e(($settings['sms_status_update'] ?? '') === 'true' ? 'checked' : ''); ?>

                                        class="sr-only peer">
                                    <div
                                        class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5">
                                    </div>
                                </div>
                            </label>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            'sms_on_confirmed' => ['✅', 'Confirmed', 'Order confirmed & being processed'],
            'sms_on_shipped' => ['🚚', 'Shipped', 'Order dispatched to courier'],
            'sms_on_out_for_delivery' => ['🛵', 'Out for Delivery', 'Order out for delivery today'],
            'sms_on_delivered' => ['🎉', 'Delivered', 'Order successfully delivered'],
            'sms_on_cancelled' => ['❌', 'Cancelled', 'Order has been cancelled'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$icon, $label, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label
                                    class="flex items-center justify-between px-4 py-3 border-b last:border-0 hover:bg-gray-50 cursor-pointer pl-10">
                                    <div class="flex items-center gap-3">
                                        <span><?php echo e($icon); ?></span>
                                        <div>
                                            <p class="text-sm text-gray-700 font-medium"><?php echo e($label); ?></p>
                                            <p class="text-xs text-gray-400"><?php echo e($desc); ?></p>
                                        </div>
                                    </div>
                                    <div class="relative flex-shrink-0 ml-4">
                                        <input type="checkbox" name="<?php echo e($key); ?>" value="true"
                                            <?php echo e(($settings[$key] ?? 'true') === 'true' ? 'checked' : ''); ?>

                                            class="sr-only peer">
                                        <div
                                            class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4">
                                        </div>
                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
                <p class="text-xs text-gray-400">Get key: <a href="https://app.mimsms.com" target="_blank"
                        class="text-teal-600 underline">app.mimsms.com</a></p>
                <button type="submit" class="btn-primary">Save SMS Settings</button>
            </form>
        </div>

        
        <div x-show="tab === 'pathao'" class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">📦 Pathao Courier API</h2>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="pathao">
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="form-label">Client ID</label><input type="text" name="pathao_client_id"
                            value="<?php echo e($settings['pathao_client_id'] ?? ''); ?>" class="form-input"></div>
                    <div><label class="form-label">Client Secret</label><input type="password"
                            name="pathao_client_secret" value="<?php echo e($settings['pathao_client_secret'] ?? ''); ?>"
                            class="form-input"></div>
                    <div><label class="form-label">Username</label><input type="text" name="pathao_username"
                            value="<?php echo e($settings['pathao_username'] ?? ''); ?>" class="form-input"></div>
                    <div><label class="form-label">Password</label><input type="password" name="pathao_password"
                            value="<?php echo e($settings['pathao_password'] ?? ''); ?>" class="form-input"></div>
                    <div><label class="form-label">Store ID</label><input type="text" name="pathao_store_id"
                            value="<?php echo e($settings['pathao_store_id'] ?? ''); ?>" class="form-input"></div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="pathao_is_live" value="true"
                        <?php echo e(($settings['pathao_is_live'] ?? '') === 'true' ? 'checked' : ''); ?> class="accent-teal-600">
                    <span class="text-sm text-gray-700">Live Mode</span>
                </label>
                <p class="text-xs text-gray-400">Credentials: <a href="https://merchant.pathao.com" target="_blank"
                        class="text-teal-600 underline">merchant.pathao.com</a></p>

                <div class="border-t pt-4">
                    <p class="text-sm font-bold text-gray-700 mb-3">Default City / Zone / Area</p>
                    <p class="text-xs text-gray-500 mb-3">These will be pre-selected when pushing an order to Pathao. You
                        can still change them per order.</p>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="form-label">Default City ID</label>
                            <input type="number" name="pathao_default_city_id"
                                value="<?php echo e($settings['pathao_default_city_id'] ?? ''); ?>" class="form-input"
                                placeholder="e.g. 1 (Dhaka)">
                            <p class="text-xs text-gray-400 mt-1">Dhaka = 1</p>
                        </div>
                        <div>
                            <label class="form-label">Default Zone ID</label>
                            <input type="number" name="pathao_default_zone_id"
                                value="<?php echo e($settings['pathao_default_zone_id'] ?? ''); ?>" class="form-input"
                                placeholder="Zone ID">
                        </div>
                        <div>
                            <label class="form-label">Default Area ID</label>
                            <input type="number" name="pathao_default_area_id"
                                value="<?php echo e($settings['pathao_default_area_id'] ?? ''); ?>" class="form-input"
                                placeholder="Optional">
                        </div>
                    </div>
                    <p class="text-xs text-blue-600 bg-blue-50 border border-blue-200 rounded-lg p-2 mt-2">
                        💡 Find IDs by pushing any order to Pathao — the city/zone dropdowns load live from the Pathao API.
                        Copy the IDs you use most often and paste them here.
                    </p>
                </div>
                <div class="border rounded-xl overflow-hidden mt-4">
                    <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-600">Webhook Configuration</p>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="form-label">Callback URL</label>
                            <div class="flex gap-2">
                                <input type="text" readonly value="<?php echo e(route('webhooks.pathao')); ?>"
                                    class="form-input font-mono text-xs bg-gray-50 text-gray-600 flex-1">
                                <button type="button"
                                    onclick="navigator.clipboard.writeText('<?php echo e(route('webhooks.pathao')); ?>').then(() => this.textContent = 'Copied!')"
                                    class="btn-outline text-xs px-3 flex-shrink-0">Copy</button>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Paste this URL in Pathao Merchant Portal → Settings →
                                Webhook</p>
                        </div>
                        <div>
                            <label class="form-label">Webhook Secret</label>
                            <div class="flex gap-2">
                                <input type="text" name="pathao_webhook_secret"
                                    value="<?php echo e($settings['pathao_webhook_secret'] ?? ''); ?>"
                                    class="form-input font-mono flex-1"
                                    placeholder="Set a strong secret, then copy it to Pathao portal">
                                <button type="button"
                                    onclick="const s = Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2); this.closest('div').querySelector('input').value = s"
                                    class="btn-secondary text-xs px-3 flex-shrink-0 whitespace-nowrap">Generate</button>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Pathao will sign each webhook request with this secret so
                                you can verify it's genuine</p>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Save Pathao Settings</button>
            </form>
        </div>

        
        <div x-show="tab === 'steadfast'" class="space-y-4">
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-1 pb-2 border-b flex items-center gap-2">
                    <span class="text-xl">📦</span> Steadfast Courier API
                </h2>
                <p class="text-xs text-gray-500 mb-4">
                    Get your API credentials from
                    <a href="https://steadfast.com.bd/" target="_blank"
                        class="text-teal-600 underline font-semibold">portal.steadfast.com.bd</a>
                    → Account → API Credentials
                </p>
                <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?> <input type="hidden" name="group" value="steadfast">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">API Key *</label>
                            <input type="text" name="steadfast_api_key"
                                value="<?php echo e($settings['steadfast_api_key'] ?? ''); ?>" class="form-input font-mono"
                                placeholder="Your Steadfast API key">
                            <p class="text-xs text-gray-400 mt-1">From portal.steadfast.com.bd → API Credentials</p>
                        </div>
                        <div>
                            <label class="form-label">Secret Key *</label>
                            <input type="password" name="steadfast_secret_key"
                                value="<?php echo e($settings['steadfast_secret_key'] ?? ''); ?>" class="form-input"
                                placeholder="Your Steadfast Secret key">
                            <p class="text-xs text-gray-400 mt-1">Keep this secret — never share</p>
                        </div>
                    </div>


                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="steadfast_enabled" value="true"
                            <?php echo e(($settings['steadfast_enabled'] ?? 'false') === 'true' ? 'checked' : ''); ?>

                            class="accent-teal-600">
                        <span class="text-sm text-gray-700 font-medium">Enable Steadfast courier</span>
                    </label>
                    <div class="border rounded-xl overflow-hidden mt-4">
                        <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                            <p class="text-xs font-semibold text-gray-600">Webhook Configuration</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <label class="form-label">Callback URL</label>
                                <div class="flex gap-2">
                                    <input type="text" readonly value="<?php echo e(route('webhooks.steadfast')); ?>"
                                        class="form-input font-mono text-xs bg-gray-50 text-gray-600 flex-1">
                                    <button type="button"
                                        onclick="navigator.clipboard.writeText('<?php echo e(route('webhooks.steadfast')); ?>').then(() => this.textContent = 'Copied!')"
                                        class="btn-outline text-xs px-3 flex-shrink-0">Copy</button>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Paste this URL in Steadfast Portal → Settings →
                                    Webhook</p>
                            </div>
                            <div>
                                <label class="form-label">Auth Token (Bearer)</label>
                                <div class="flex gap-2">
                                    <input type="text" name="steadfast_bearer_token"
                                        value="<?php echo e($settings['steadfast_bearer_token'] ?? ''); ?>"
                                        class="form-input font-mono flex-1"
                                        placeholder="Set a token, then copy it to Steadfast portal">
                                    <button type="button"
                                        onclick="const s = Math.random().toString(36).slice(2)+Math.random().toString(36).slice(2); this.closest('div').querySelector('input').value = s"
                                        class="btn-secondary text-xs px-3 flex-shrink-0 whitespace-nowrap">Generate</button>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Steadfast sends this token in the <code
                                        class="bg-gray-100 px-1 rounded">Authorization: Bearer</code> header — you verify
                                    it to confirm the request is genuine</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Save Steadfast Settings</button>
                </form>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['steadfast_api_key'])): ?>
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-3 text-sm">Connection Status</h3>
                    <div class="flex items-center gap-3">
                        <div
                            class="flex items-center gap-2 <?php echo e(($settings['steadfast_enabled'] ?? '') === 'true' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-gray-50 border-gray-200 text-gray-500'); ?> border rounded-xl px-4 py-2.5">
                            <span
                                class="text-lg"><?php echo e(($settings['steadfast_enabled'] ?? '') === 'true' ? '🟢' : '⚫'); ?></span>
                            <div>
                                <p class="text-xs font-bold">Steadfast</p>
                                <p class="text-sm font-black">
                                    <?php echo e(($settings['steadfast_enabled'] ?? '') === 'true' ? 'Enabled' : 'Disabled'); ?></p>
                            </div>
                        </div>
                        <a href="https://portal.steadfast.com.bd" target="_blank"
                            class="flex items-center gap-2 bg-gray-50 border rounded-xl px-4 py-2.5 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-external-link-alt text-gray-500 text-sm"></i>
                            <span class="text-sm font-semibold text-gray-700">Open Steadfast Portal</span>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Enter your API Key and Secret Key above to activate Steadfast courier integration.
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <div class="bg-white rounded-xl border p-5">
                <h3 class="font-bold text-gray-800 mb-3 text-sm">How to Use</h3>
                <ol class="space-y-2 text-xs text-gray-600 list-decimal list-inside">
                    <li>Get API Key & Secret Key from <a href="https://portal.steadfast.com.bd" target="_blank"
                            class="text-teal-600 underline">Steadfast Portal</a> → Account → API Credentials</li>
                    <li>Enter them above and save</li>
                    <li>Go to any order → click <strong>📦 Steadfast</strong> button to push the order</li>
                    <li>Once pushed, a <strong>Print Label</strong> button appears — click to download the shipping label
                        PDF</li>
                    <li>Use <strong>↻ Sync Steadfast</strong> to update the delivery status from Steadfast</li>
                </ol>
            </div>
        </div>

        
        <div x-show="tab === 'pixel'" class="space-y-4">

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-1 pb-2 border-b flex items-center gap-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#1877F2">
                        <path
                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                    </svg>
                    Meta Pixel Configuration
                </h2>
                <p class="text-xs text-gray-500 mb-4">Connect your Facebook Pixel to track conversions. Purchase events
                    fire automatically on order success.</p>

                <form method="POST" action="<?php echo e(route('admin.settings.pixel')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>

                    
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <p class="text-sm font-bold text-gray-800 mb-3">Mode</p>
                        <div class="flex gap-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['live' => ['🟢 Live Mode', 'bg-green-50 border-green-300 text-green-800'], 'test' => ['🧪 Test Mode', 'bg-yellow-50 border-yellow-300 text-yellow-800']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => [$mlabel, $mclass]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="meta_pixel_mode" value="<?php echo e($mode); ?>"
                                        <?php echo e(($settings['meta_pixel_mode'] ?? 'test') === $mode ? 'checked' : ''); ?>

                                        class="sr-only peer">
                                    <div
                                        class="border-2 rounded-xl p-3 text-center text-sm font-bold transition-all peer-checked:border-teal-500 peer-checked:bg-teal-50 <?php echo e($mclass); ?>">
                                        <?php echo e($mlabel); ?>

                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="mt-3" id="test-code-wrap" x-data
                            x-show="<?php echo e(($settings['meta_pixel_mode'] ?? 'test') === 'test' ? 'true' : 'false'); ?>">
                            <label class="form-label">Test Event Code</label>
                            <input type="text" name="meta_pixel_test_event_code"
                                value="<?php echo e($settings['meta_pixel_test_event_code'] ?? ''); ?>" class="form-input font-mono"
                                placeholder="TEST12345">
                            <p class="text-xs text-gray-400 mt-1">Get from Events Manager → Test Events → Enter code here
                            </p>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Pixel ID *</label>
                            <input type="text" name="meta_pixel_id" value="<?php echo e($settings['meta_pixel_id'] ?? ''); ?>"
                                class="form-input font-mono text-lg" placeholder="1234567890123456">
                            <p class="text-xs text-gray-400 mt-1">Events Manager → Data Sources → your Pixel → ID</p>
                        </div>
                        <div>
                            <label class="form-label">Conversions API Access Token</label>
                            <input type="password" name="meta_access_token"
                                value="<?php echo e($settings['meta_access_token'] ?? ''); ?>" class="form-input"
                                placeholder="Optional — for server-side events">
                            <p class="text-xs text-gray-400 mt-1">Events Manager → Settings → Conversions API</p>
                        </div>
                    </div>

                    
                    <div>
                        <p class="text-sm font-bold text-gray-800 mb-3">Events to Fire</p>
                        <div class="grid grid-cols-2 gap-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            'meta_pixel_page_view' => ['PageView', 'Every page load', true, 'bg-teal-50 border-teal-200'],
            'meta_pixel_view_content' => ['ViewContent', 'Product detail page', true, 'bg-teal-50 border-teal-200'],
            'meta_pixel_search' => ['Search', 'Live search & product search', true, 'bg-teal-50 border-teal-200'],
            'meta_pixel_add_to_cart' => ['AddToCart', 'Add to cart button click', true, 'bg-orange-50 border-orange-200'],
            'meta_pixel_initiate_checkout' => ['InitiateCheckout', 'Checkout page load', true, 'bg-orange-50 border-orange-200'],
            'meta_pixel_purchase' => ['Purchase', 'Order placed (most important!)', true, 'bg-green-50 border-green-200'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$event, $desc, $default, $colors]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $enabled = ($settings[$key] ?? ($default ? 'true' : 'false')) === 'true'; ?>
                                <div class="flex items-start justify-between p-3 border rounded-xl <?php echo e($colors); ?>">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 font-mono"><?php echo e($event); ?></p>
                                        <p class="text-xs text-gray-500 mt-0.5"><?php echo e($desc); ?></p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer ml-2 flex-shrink-0">
                                        <input type="checkbox" name="<?php echo e($key); ?>" value="true"
                                            <?php echo e($enabled ? 'checked' : ''); ?> class="sr-only peer">
                                        <div
                                            class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4">
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary">Save Pixel Settings</button>
                </form>
            </div>
            
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['meta_pixel_id'])): ?>
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-3">Pixel Status</h3>
                    <div class="flex flex-wrap gap-3">
                        <div
                            class="flex items-center gap-2 bg-<?php echo e(($settings['meta_pixel_mode'] ?? '') === 'live' ? 'green' : 'yellow'); ?>-50 border border-<?php echo e(($settings['meta_pixel_mode'] ?? '') === 'live' ? 'green' : 'yellow'); ?>-200 rounded-xl px-4 py-2.5">
                            <span
                                class="text-lg"><?php echo e(($settings['meta_pixel_mode'] ?? '') === 'live' ? '🟢' : '🧪'); ?></span>
                            <div>
                                <p class="text-xs font-bold">Mode</p>
                                <p class="text-sm font-black"><?php echo e(strtoupper($settings['meta_pixel_mode'] ?? 'test')); ?>

                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-xl px-4 py-2.5">
                            <span class="text-lg">🔢</span>
                            <div>
                                <p class="text-xs font-bold">Pixel ID</p>
                                <p class="text-sm font-mono font-black"><?php echo e($settings['meta_pixel_id']); ?></p>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['meta_pixel_test_event_code']) && ($settings['meta_pixel_mode'] ?? '') === 'test'): ?>
                            <div
                                class="flex items-center gap-2 bg-yellow-50 border border-yellow-200 rounded-xl px-4 py-2.5">
                                <span class="text-lg">🔑</span>
                                <div>
                                    <p class="text-xs font-bold">Test Code</p>
                                    <p class="text-sm font-mono font-black"><?php echo e($settings['meta_pixel_test_event_code']); ?>

                                    </p>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <a href="https://business.facebook.com/events_manager" target="_blank"
                            class="flex items-center gap-2 bg-gray-50 border rounded-xl px-4 py-2.5 hover:bg-gray-100 transition-colors">
                            <i class="fas fa-external-link-alt text-gray-500 text-sm"></i>
                            <span class="text-sm font-semibold text-gray-700">Open Events Manager</span>
                        </a>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
<div x-show="tab === 'notifications'" x-cloak>
    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50 flex items-center gap-2">
            <span class="text-lg">🔔</span>
            <div>
                <h2 class="font-bold text-gray-800 text-sm">Admin Notifications</h2>
                <p class="text-xs text-gray-400">Email alerts sent to you when things happen</p>
            </div>
        </div>
        <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6 space-y-5">
            <?php echo csrf_field(); ?> <input type="hidden" name="group" value="notifications">

            <div>
                <label class="form-label">Notification Email</label>
                <input type="email" name="admin_notification_email"
                    value="<?php echo e($settings['admin_notification_email'] ?? $settings['site_email'] ?? ''); ?>"
                    class="form-input" placeholder="admin@ousodhaloy.com">
                <p class="text-xs text-gray-400 mt-1">All admin notifications are sent to this address</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Email Triggers</p>
                <label class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50 cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">🛍️</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">New Order Placed</p>
                            <p class="text-xs text-gray-400">Receive an email every time a customer places an order</p>
                        </div>
                    </div>
                    <div class="relative flex-shrink-0 ml-4">
                        <input type="checkbox" name="email_new_order" value="true"
                            <?php echo e(($settings['email_new_order'] ?? 'true') === 'true' ? 'checked' : ''); ?> class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                    </div>
                </label>
            </div>

            <div class="flex justify-end border-t pt-4">
                <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Notification Settings</button>
            </div>
        </form>
    </div>
</div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Toggle test code visibility when mode changes
        document.querySelectorAll('[name=meta_pixel_mode]').forEach(r => {
            r.addEventListener('change', () => {
                const wrap = document.getElementById('test-code-wrap');
                if (wrap) wrap.style.display = r.value === 'test' ? 'block' : 'none';
            });
        });

        // Build checkout fields JSON before form submit
        function buildCheckoutJson() {
            const fields = {};
            document.querySelectorAll('[name^="checkout_field["]').forEach(el => {
                const match = el.name.match(/checkout_field\[([^\]]+)\]\[([^\]]+)\]/);
                if (!match) return;
                const [, key, prop] = match;
                if (!fields[key]) fields[key] = {
                    visible: false,
                    required: false
                };
                if (el.checked) fields[key][prop] = true;
            });
            document.getElementById('checkout-fields-json').value = JSON.stringify(fields);
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>