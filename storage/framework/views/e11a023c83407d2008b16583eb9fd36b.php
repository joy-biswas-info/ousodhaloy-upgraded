<?php $__env->startSection('page-title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4" x-data="{ tab: '<?php echo e(request('tab', 'general')); ?>' }">

    
    <div class="bg-white rounded-xl border p-1.5 flex gap-1 overflow-x-auto">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
            'general'   => ['🌐', 'General'],
            'orders'    => ['🚚', 'Delivery'],
            'payment'   => ['💳', 'Payments'],
            'checkout'  => ['🛒', 'Checkout'],
            'sms'       => ['📱', 'SMS'],
            'pathao'    => ['📦', 'Pathao'],
            'steadfast' => ['📦', 'Steadfast'],
            'pixel'     => ['📊', 'Pixel'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$icon, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button @click="tab = '<?php echo e($key); ?>'"
            :class="tab === '<?php echo e($key); ?>'
                ? 'bg-teal-600 text-white shadow-sm'
                : 'text-gray-500 hover:bg-gray-100 hover:text-gray-700'"
            class="flex-shrink-0 flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs font-semibold transition-all whitespace-nowrap">
            <span><?php echo e($icon); ?></span> <?php echo e($label); ?>

        </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div x-show="tab === 'general'" x-cloak>
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center gap-2">
                <span class="text-lg">🌐</span>
                <div>
                    <h2 class="font-bold text-gray-800 text-sm">General Settings</h2>
                    <p class="text-xs text-gray-400">Site identity, contact info, and display options</p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" enctype="multipart/form-data" class="p-6 space-y-5">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="general">

                
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Site Identity</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" value="<?php echo e($settings['site_name'] ?? ''); ?>" class="form-input" placeholder="Ousodhaloy">
                        </div>
                        <div>
                            <label class="form-label">Tagline</label>
                            <input type="text" name="site_tagline" value="<?php echo e($settings['site_tagline'] ?? ''); ?>" class="form-input" placeholder="Your trusted pharmacy">
                        </div>
                    </div>
                </div>

                
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Contact</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Phone</label>
                            <div class="relative">
                                <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" name="site_phone" value="<?php echo e($settings['site_phone'] ?? ''); ?>" class="form-input pl-8" placeholder="01XXXXXXXXX">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <div class="relative">
                                <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="email" name="site_email" value="<?php echo e($settings['site_email'] ?? ''); ?>" class="form-input pl-8" placeholder="hello@example.com">
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Address</label>
                            <div class="relative">
                                <i class="fas fa-map-marker-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" name="site_address" value="<?php echo e($settings['site_address'] ?? ''); ?>" class="form-input pl-8" placeholder="123 Main Road, Dhaka">
                            </div>
                        </div>
                    </div>
                </div>

                
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Branding & Chat</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Site Logo</label>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['site_logo'])): ?>
                            <div class="flex items-center gap-3 mb-2 p-2 bg-gray-50 rounded-lg border">
                                <img src="<?php echo e(asset('storage/'.$settings['site_logo'])); ?>" class="h-10 object-contain">
                                <span class="text-xs text-gray-400">Current logo</span>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <input type="file" name="site_logo" accept="image/*" class="form-input py-1.5 text-xs">
                        </div>
                        <div>
                            <label class="form-label">Messenger / WhatsApp URL</label>
                            <div class="relative">
                                <i class="fas fa-comment absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="url" name="messenger_url" value="<?php echo e($settings['messenger_url'] ?? ''); ?>" class="form-input pl-8" placeholder="https://wa.me/8801...">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Shows a floating chat button. Leave blank to hide.</p>
                        </div>
                    </div>
                </div>

                
                <div class="border-t pt-4">
                    <label class="flex items-center justify-between p-3.5 rounded-xl border hover:bg-gray-50 transition-colors cursor-pointer">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">🔧 Maintenance Mode</p>
                            <p class="text-xs text-gray-400 mt-0.5">Shows a maintenance page to all visitors</p>
                        </div>
                        <div class="relative flex-shrink-0 ml-4">
                            <input type="checkbox" name="maintenance_mode" value="true"
                                <?php echo e(($settings['maintenance_mode'] ?? '') === 'true' ? 'checked' : ''); ?> class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </div>
                    </label>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save General Settings</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="tab === 'orders'" x-cloak class="space-y-4">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center gap-2">
                <span class="text-lg">🚚</span>
                <div>
                    <h2 class="font-bold text-gray-800 text-sm">Delivery Settings</h2>
                    <p class="text-xs text-gray-400">Charges, free delivery threshold, and guest checkout</p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6 space-y-5">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="orders">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">Default Delivery Charge</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">৳</span>
                            <input type="number" name="delivery_charge" value="<?php echo e($settings['delivery_charge'] ?? 60); ?>" class="form-input pl-7">
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Free Delivery Above</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">৳</span>
                            <input type="number" name="free_delivery_min" value="<?php echo e($settings['free_delivery_min'] ?? 500); ?>" class="form-input pl-7">
                        </div>
                    </div>
                </div>

                <label class="flex items-center justify-between p-3.5 rounded-xl border hover:bg-gray-50 transition-colors cursor-pointer">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">👤 Guest Checkout</p>
                        <p class="text-xs text-gray-400 mt-0.5">Allow orders without creating an account</p>
                    </div>
                    <div class="relative flex-shrink-0 ml-4">
                        <input type="checkbox" name="guest_checkout" value="true"
                            <?php echo e(($settings['guest_checkout'] ?? '') === 'true' ? 'checked' : ''); ?> class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                    </div>
                </label>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Delivery Settings</button>
                </div>
            </form>
        </div>

        
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">🗺️</span>
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm">Delivery Zones</h3>
                        <p class="text-xs text-gray-400">Per-division charges override the default above</p>
                    </div>
                </div>
                <span class="text-xs text-gray-400"><?php echo e(count($deliveryZones)); ?> zones</span>
            </div>
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Zone</th>
                            <th>Division</th>
                            <th>Charge</th>
                            <th>Free Above</th>
                            <th>Est. Days</th>
                            <th>Status</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $deliveryZones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $zone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr x-data="{ editing: false }">
                            <td class="font-semibold text-sm"><?php echo e($zone->name); ?></td>
                            <td class="text-xs text-gray-500"><?php echo e($zone->division); ?></td>
                            <td>
                                <span x-show="!editing" class="font-mono text-sm">৳<?php echo e($zone->delivery_charge); ?></span>
                                <form x-show="editing" method="POST" action="<?php echo e(route('admin.settings.delivery-zones.update', $zone)); ?>" class="flex gap-1.5 items-center flex-wrap">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                    <input type="hidden" name="name" value="<?php echo e($zone->name); ?>">
                                    <input type="hidden" name="division" value="<?php echo e($zone->division); ?>">
                                    <input type="number" name="delivery_charge" value="<?php echo e($zone->delivery_charge); ?>" step="0.01" class="form-input py-1 text-xs" style="width:75px" placeholder="Charge">
                                    <input type="number" name="free_delivery_above" value="<?php echo e($zone->free_delivery_above); ?>" step="0.01" class="form-input py-1 text-xs" style="width:75px" placeholder="Free above">
                                    <input type="number" name="estimated_days" value="<?php echo e($zone->estimated_days); ?>" class="form-input py-1 text-xs" style="width:55px" placeholder="Days">
                                    <input type="hidden" name="is_active" value="<?php echo e($zone->is_active ? '1' : '0'); ?>">
                                    <button type="submit" class="btn-primary btn-sm">Save</button>
                                    <button type="button" @click="editing=false" class="btn-outline btn-sm">Cancel</button>
                                </form>
                            </td>
                            <td x-show="!editing" class="font-mono text-sm">৳<?php echo e($zone->free_delivery_above); ?></td>
                            <td x-show="!editing" class="text-sm text-gray-500"><?php echo e($zone->estimated_days); ?>d</td>
                            <td>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold <?php echo e($zone->is_active ? 'text-green-700 bg-green-50' : 'text-gray-400 bg-gray-50'); ?> px-2 py-0.5 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full <?php echo e($zone->is_active ? 'bg-green-500' : 'bg-gray-300'); ?>"></span>
                                    <?php echo e($zone->is_active ? 'Active' : 'Off'); ?>

                                </span>
                            </td>
                            <td x-show="!editing" class="text-right">
                                <div class="flex gap-1.5 justify-end">
                                    <button type="button" @click="editing=true" class="btn-secondary btn-sm">Edit</button>
                                    <form method="POST" action="<?php echo e(route('admin.settings.delivery-zones.destroy', $zone)); ?>" onsubmit="return confirm('Delete this zone?')">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button class="btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t bg-gray-50">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Add New Zone</p>
                <form method="POST" action="<?php echo e(route('admin.settings.delivery-zones.store')); ?>" class="grid grid-cols-6 gap-2 items-end">
                    <?php echo csrf_field(); ?>
                    <div class="col-span-2">
                        <label class="form-label">Zone Name</label>
                        <input type="text" name="name" class="form-input" placeholder="e.g. Dhaka City" required>
                    </div>
                    <div>
                        <label class="form-label">Division</label>
                        <select name="division" class="form-select" required>
                            <option value="">Select</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('bd.divisions'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($div); ?>"><?php echo e($div); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Charge (৳)</label>
                        <input type="number" name="delivery_charge" class="form-input" placeholder="60" required>
                    </div>
                    <div>
                        <label class="form-label">Free Above (৳)</label>
                        <input type="number" name="free_delivery_above" value="500" class="form-input">
                    </div>
                    <div>
                        <button type="submit" class="btn-secondary w-full">Add Zone</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div x-show="tab === 'payment'" x-cloak>
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center gap-2">
                <span class="text-lg">💳</span>
                <div>
                    <h2 class="font-bold text-gray-800 text-sm">Payment Methods</h2>
                    <p class="text-xs text-gray-400">Enable or disable checkout payment options</p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6 space-y-5">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="payment">

                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                        'cod_enabled'  => ['💵', 'Cash on Delivery', 'Customer pays when the order arrives'],
                        'ssl_enabled'  => ['💳', 'SSL Commerz (Card / bKash / Nagad)', 'Online card, mobile banking, net banking'],
                        'bank_enabled' => ['🏦', 'Bank Transfer', 'Manual bank transfer with proof upload'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$icon, $label, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50 transition-colors cursor-pointer group">
                        <div class="flex items-center gap-3">
                            <span class="text-xl w-8 text-center"><?php echo e($icon); ?></span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800"><?php echo e($label); ?></p>
                                <p class="text-xs text-gray-400 mt-0.5"><?php echo e($desc); ?></p>
                            </div>
                        </div>
                        <div class="relative flex-shrink-0 ml-4">
                            <input type="checkbox" name="<?php echo e($key); ?>" value="true"
                                <?php echo e(($settings[$key] ?? 'true') === 'true' ? 'checked' : ''); ?> class="sr-only peer">
                            <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="border rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <p class="text-xs font-semibold text-gray-600">SSL Commerz Credentials</p>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Store ID</label>
                                <input type="text" name="ssl_store_id" value="<?php echo e($settings['ssl_store_id'] ?? ''); ?>" class="form-input font-mono" placeholder="yourstore">
                            </div>
                            <div>
                                <label class="form-label">Store Password</label>
                                <input type="password" name="ssl_store_pass" value="<?php echo e($settings['ssl_store_pass'] ?? ''); ?>" class="form-input" placeholder="••••••••">
                            </div>
                        </div>
                        <label class="flex items-center gap-2.5 cursor-pointer text-sm">
                            <input type="checkbox" name="ssl_is_live" value="true"
                                <?php echo e(($settings['ssl_is_live'] ?? '') === 'true' ? 'checked' : ''); ?> class="accent-teal-600 w-4 h-4">
                            <span class="text-gray-700 font-medium">Live Mode</span>
                            <span class="text-xs text-gray-400">(uncheck to use sandbox)</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Payment Settings</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="tab === 'checkout'" x-cloak>
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center gap-2">
                <span class="text-lg">🛒</span>
                <div>
                    <h2 class="font-bold text-gray-800 text-sm">Checkout Fields</h2>
                    <p class="text-xs text-gray-400">Control which fields appear on the checkout form</p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="checkout">
                <?php
                    $fields = [
                        ['key' => 'shipping_email',    'label' => 'Email Address',       'icon' => 'fa-envelope'],
                        ['key' => 'shipping_postcode', 'label' => 'Postal / ZIP Code',   'icon' => 'fa-map-pin'],
                        ['key' => 'shipping_upazila',  'label' => 'Upazila / Area',      'icon' => 'fa-map'],
                        ['key' => 'prescription',      'label' => 'Prescription Upload', 'icon' => 'fa-file-medical'],
                        ['key' => 'customer_note',     'label' => 'Order Notes',         'icon' => 'fa-sticky-note'],
                        ['key' => 'promo_code',        'label' => 'Promo Code Field',    'icon' => 'fa-tag'],
                    ];
                    $cf = json_decode($settings['checkout_fields'] ?? '{}', true) ?: [];
                ?>

                
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Always Required</p>
                <div class="grid grid-cols-2 gap-2 mb-5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Full Name', 'Phone Number', 'Division', 'District', 'Full Address']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-2.5 p-3 bg-teal-50 border border-teal-100 rounded-xl">
                        <i class="fas fa-check-circle text-teal-500 text-sm"></i>
                        <span class="text-sm font-medium text-teal-800"><?php echo e($f); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Optional Fields</p>
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $visible  = $cf[$f['key']]['visible']  ?? true;
                        $required = $cf[$f['key']]['required'] ?? false;
                    ?>
                    <div class="flex items-center justify-between p-3.5 border rounded-xl hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-2.5">
                            <i class="fas <?php echo e($f['icon']); ?> text-gray-400 text-sm w-4 text-center"></i>
                            <span class="text-sm font-medium text-gray-700"><?php echo e($f['label']); ?></span>
                        </div>
                        <div class="flex items-center gap-5">
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-gray-500">
                                <input type="checkbox" name="checkout_field[<?php echo e($f['key']); ?>][visible]" value="1"
                                    <?php echo e($visible ? 'checked' : ''); ?> class="accent-teal-600 w-3.5 h-3.5">
                                Show
                            </label>
                            <label class="flex items-center gap-1.5 cursor-pointer text-xs text-gray-500">
                                <input type="checkbox" name="checkout_field[<?php echo e($f['key']); ?>][required]" value="1"
                                    <?php echo e($required ? 'checked' : ''); ?> class="accent-teal-600 w-3.5 h-3.5">
                                Required
                            </label>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <input type="hidden" name="checkout_fields" id="checkout-fields-json">
                <div class="flex justify-end border-t mt-5 pt-4">
                    <button type="submit" onclick="buildCheckoutJson()" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Checkout Settings</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="tab === 'sms'" x-cloak>
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center gap-2">
                <span class="text-lg">📱</span>
                <div>
                    <h2 class="font-bold text-gray-800 text-sm">MimSMS Settings</h2>
                    <p class="text-xs text-gray-400">Configure SMS notifications sent to customers</p>
                </div>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6 space-y-5">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="sms">

                <div class="border rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                        <p class="text-xs font-semibold text-gray-600">API Credentials</p>
                        <a href="https://app.mimsms.com" target="_blank" class="text-xs text-teal-600 hover:underline">
                            app.mimsms.com <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                        </a>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Username</label>
                            <input type="text" name="mimsms_username" value="<?php echo e($settings['mimsms_username'] ?? ''); ?>" class="form-input" placeholder="Your MimSMS username">
                        </div>
                        <div>
                            <label class="form-label">API Key</label>
                            <input type="password" name="mimsms_api_key" value="<?php echo e($settings['mimsms_api_key'] ?? ''); ?>" class="form-input" placeholder="••••••••••••">
                        </div>
                        <div>
                            <label class="form-label">Sender ID</label>
                            <input type="text" name="mimsms_sender_id" value="<?php echo e($settings['mimsms_sender_id'] ?? 'Ousodhaloy'); ?>" class="form-input">
                        </div>
                        <div class="flex items-end pb-0.5">
                            <?php $hasKey = !empty($settings['mimsms_api_key']); ?>
                            <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl border <?php echo e($hasKey ? 'bg-green-50 border-green-200 text-green-700' : 'bg-gray-50 border-gray-200 text-gray-400'); ?> text-xs font-semibold w-full">
                                <span class="w-2 h-2 rounded-full <?php echo e($hasKey ? 'bg-green-500' : 'bg-gray-300'); ?>"></span>
                                <?php echo e($hasKey ? 'API key saved' : 'No API key set'); ?>

                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Notification Triggers</p>
                    <div class="space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            'sms_order_confirm'  => ['📦', 'Order Placed', 'Sent when a customer places a new order'],
                            'sms_status_update'  => ['🔄', 'Status Updates', 'Sent on confirmed, shipped, delivered, cancelled'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$icon, $label, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <span class="text-lg"><?php echo e($icon); ?></span>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800"><?php echo e($label); ?></p>
                                    <p class="text-xs text-gray-400 mt-0.5"><?php echo e($desc); ?></p>
                                </div>
                            </div>
                            <div class="relative flex-shrink-0 ml-4">
                                <input type="checkbox" name="<?php echo e($key); ?>" value="true"
                                    <?php echo e(($settings[$key] ?? '') === 'true' ? 'checked' : ''); ?> class="sr-only peer">
                                <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save SMS Settings</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="tab === 'pathao'" x-cloak>
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📦</span>
                    <div>
                        <h2 class="font-bold text-gray-800 text-sm">Pathao Courier API</h2>
                        <p class="text-xs text-gray-400">Credentials for pushing orders to Pathao</p>
                    </div>
                </div>
                <a href="https://merchant.pathao.com" target="_blank" class="text-xs text-teal-600 hover:underline">
                    merchant.pathao.com <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                </a>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6 space-y-5">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="pathao">

                <div class="border rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <p class="text-xs font-semibold text-gray-600">API Credentials</p>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Client ID</label>
                            <input type="text" name="pathao_client_id" value="<?php echo e($settings['pathao_client_id'] ?? ''); ?>" class="form-input font-mono">
                        </div>
                        <div>
                            <label class="form-label">Client Secret</label>
                            <input type="password" name="pathao_client_secret" value="<?php echo e($settings['pathao_client_secret'] ?? ''); ?>" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Username</label>
                            <input type="text" name="pathao_username" value="<?php echo e($settings['pathao_username'] ?? ''); ?>" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Password</label>
                            <input type="password" name="pathao_password" value="<?php echo e($settings['pathao_password'] ?? ''); ?>" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Store ID</label>
                            <input type="text" name="pathao_store_id" value="<?php echo e($settings['pathao_store_id'] ?? ''); ?>" class="form-input font-mono">
                        </div>
                        <div class="flex items-end pb-0.5">
                            <label class="flex items-center gap-2.5 cursor-pointer text-sm">
                                <input type="checkbox" name="pathao_is_live" value="true"
                                    <?php echo e(($settings['pathao_is_live'] ?? '') === 'true' ? 'checked' : ''); ?> class="accent-teal-600 w-4 h-4">
                                <span class="text-gray-700 font-medium">Live Mode</span>
                                <span class="text-xs text-gray-400">(uncheck for sandbox)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="border rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <p class="text-xs font-semibold text-gray-600">Default Pickup Location</p>
                        <p class="text-xs text-gray-400 mt-0.5">Pre-filled when pushing orders — can still be changed per order</p>
                    </div>
                    <div class="p-4 grid grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">City ID</label>
                            <input type="number" name="pathao_default_city_id" value="<?php echo e($settings['pathao_default_city_id'] ?? ''); ?>" class="form-input font-mono" placeholder="1">
                            <p class="text-xs text-gray-400 mt-1">Dhaka = 1</p>
                        </div>
                        <div>
                            <label class="form-label">Zone ID</label>
                            <input type="number" name="pathao_default_zone_id" value="<?php echo e($settings['pathao_default_zone_id'] ?? ''); ?>" class="form-input font-mono" placeholder="Zone ID">
                        </div>
                        <div>
                            <label class="form-label">Area ID</label>
                            <input type="number" name="pathao_default_area_id" value="<?php echo e($settings['pathao_default_area_id'] ?? ''); ?>" class="form-input font-mono" placeholder="Optional">
                        </div>
                    </div>
                    <div class="px-4 pb-4">
                        <p class="text-xs text-blue-600 bg-blue-50 border border-blue-100 rounded-lg p-2.5">
                            <i class="fas fa-lightbulb mr-1"></i> Find IDs by pushing any order to Pathao — the dropdowns load live from the Pathao API. Copy the IDs you use most often and save them here.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Pathao Settings</button>
                </div>
            </form>
        </div>
    </div>

    
    <div x-show="tab === 'steadfast'" x-cloak class="space-y-4">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-lg">📦</span>
                    <div>
                        <h2 class="font-bold text-gray-800 text-sm">Steadfast Courier API</h2>
                        <p class="text-xs text-gray-400">Push orders directly to Steadfast for delivery</p>
                    </div>
                </div>
                <a href="https://portal.steadfast.com.bd" target="_blank" class="text-xs text-teal-600 hover:underline">
                    portal.steadfast.com.bd <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                </a>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>" class="p-6 space-y-5">
                <?php echo csrf_field(); ?> <input type="hidden" name="group" value="steadfast">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label">API Key</label>
                        <input type="text" name="steadfast_api_key" value="<?php echo e($settings['steadfast_api_key'] ?? ''); ?>" class="form-input font-mono" placeholder="Your Steadfast API key">
                    </div>
                    <div>
                        <label class="form-label">Secret Key</label>
                        <input type="password" name="steadfast_secret_key" value="<?php echo e($settings['steadfast_secret_key'] ?? ''); ?>" class="form-input" placeholder="••••••••••••">
                    </div>
                </div>

                <label class="flex items-center justify-between p-4 border rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">📦</span>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Enable Steadfast</p>
                            <p class="text-xs text-gray-400 mt-0.5">Show Steadfast push button on order pages</p>
                        </div>
                    </div>
                    <div class="relative flex-shrink-0 ml-4">
                        <input type="checkbox" name="steadfast_enabled" value="true"
                            <?php echo e(($settings['steadfast_enabled'] ?? 'false') === 'true' ? 'checked' : ''); ?> class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                    </div>
                </label>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Steadfast Settings</button>
                </div>
            </form>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['steadfast_api_key'])): ?>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Connection Status</p>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl <?php echo e(($settings['steadfast_enabled'] ?? '') === 'true' ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200'); ?>">
                    <span class="w-2 h-2 rounded-full <?php echo e(($settings['steadfast_enabled'] ?? '') === 'true' ? 'bg-green-500' : 'bg-gray-300'); ?>"></span>
                    <span class="text-sm font-bold <?php echo e(($settings['steadfast_enabled'] ?? '') === 'true' ? 'text-green-700' : 'text-gray-500'); ?>">
                        <?php echo e(($settings['steadfast_enabled'] ?? '') === 'true' ? 'Steadfast Enabled' : 'Steadfast Disabled'); ?>

                    </span>
                </div>
                <a href="https://portal.steadfast.com.bd" target="_blank" class="flex items-center gap-2 px-4 py-2.5 border rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors text-sm font-semibold text-gray-700">
                    <i class="fas fa-external-link-alt text-gray-400 text-xs"></i> Open Portal
                </a>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">How to Use</p>
            <ol class="space-y-2.5 text-xs text-gray-600">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                    ['Get API Key & Secret from', 'Steadfast Portal', 'https://portal.steadfast.com.bd', '→ Account → API Credentials'],
                    ['Enter credentials above and save', '', '', ''],
                    ['Go to any order and click the', 'Steadfast button', '', 'to push it'],
                    ['Click', 'Print Label', '', 'to download the shipping label PDF'],
                    ['Use', '↻ Sync Steadfast', '', 'to update delivery status from Steadfast'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="flex items-start gap-3">
                    <span class="w-5 h-5 rounded-full bg-teal-100 text-teal-700 text-[10px] font-bold flex items-center justify-center flex-shrink-0 mt-0.5"><?php echo e($i + 1); ?></span>
                    <span>
                        <?php echo e($step[0]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step[1] && $step[2]): ?> <a href="<?php echo e($step[2]); ?>" target="_blank" class="text-teal-600 font-semibold hover:underline"><?php echo e($step[1]); ?></a>
                        <?php elseif($step[1]): ?> <strong><?php echo e($step[1]); ?></strong> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php echo e($step[3]); ?>

                    </span>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ol>
        </div>
    </div>

    
    <div x-show="tab === 'pixel'" x-cloak class="space-y-4">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="#1877F2"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    <div>
                        <h2 class="font-bold text-gray-800 text-sm">Meta Pixel</h2>
                        <p class="text-xs text-gray-400">Track conversions and ad performance</p>
                    </div>
                </div>
                <a href="https://business.facebook.com/events_manager" target="_blank" class="text-xs text-teal-600 hover:underline">
                    Events Manager <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                </a>
            </div>
            <form method="POST" action="<?php echo e(route('admin.settings.pixel')); ?>" class="p-6 space-y-5">
                <?php echo csrf_field(); ?>

                
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Mode</p>
                    <div class="grid grid-cols-2 gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['live' => ['🟢', 'Live Mode', 'green'], 'test' => ['🧪', 'Test Mode', 'yellow']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => [$icon, $label, $color]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="cursor-pointer">
                            <input type="radio" name="meta_pixel_mode" value="<?php echo e($mode); ?>"
                                <?php echo e(($settings['meta_pixel_mode'] ?? 'test') === $mode ? 'checked' : ''); ?> class="sr-only peer">
                            <div class="border-2 rounded-xl p-3.5 text-center transition-all peer-checked:border-teal-500 peer-checked:bg-teal-50 border-gray-200 hover:border-gray-300">
                                <span class="text-xl block mb-1"><?php echo e($icon); ?></span>
                                <p class="text-sm font-bold text-gray-800"><?php echo e($label); ?></p>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="mt-3" id="test-code-wrap"
                        style="display: <?php echo e(($settings['meta_pixel_mode'] ?? 'test') === 'test' ? 'block' : 'none'); ?>">
                        <label class="form-label">Test Event Code</label>
                        <input type="text" name="meta_pixel_test_event_code"
                            value="<?php echo e($settings['meta_pixel_test_event_code'] ?? ''); ?>"
                            class="form-input font-mono" placeholder="TEST12345">
                        <p class="text-xs text-gray-400 mt-1">Events Manager → Test Events tab → copy the code</p>
                    </div>
                </div>

                
                <div class="border rounded-xl overflow-hidden">
                    <div class="px-4 py-3 bg-gray-50 border-b">
                        <p class="text-xs font-semibold text-gray-600">Pixel Configuration</p>
                    </div>
                    <div class="p-4 grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Pixel ID *</label>
                            <input type="text" name="meta_pixel_id" value="<?php echo e($settings['meta_pixel_id'] ?? ''); ?>" class="form-input font-mono" placeholder="1234567890123456">
                            <p class="text-xs text-gray-400 mt-1">Events Manager → Data Sources → Pixel → ID</p>
                        </div>
                        <div>
                            <label class="form-label">Conversions API Token <span class="font-normal normal-case text-gray-400">(optional)</span></label>
                            <input type="password" name="meta_access_token" value="<?php echo e($settings['meta_access_token'] ?? ''); ?>" class="form-input" placeholder="For server-side events">
                            <p class="text-xs text-gray-400 mt-1">Events Manager → Settings → Conversions API</p>
                        </div>
                    </div>
                </div>

                
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Events to Fire</p>
                    <div class="grid grid-cols-2 gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [
                            'meta_pixel_page_view'         => ['PageView',         'Every page load',            'bg-gray-50  border-gray-200'],
                            'meta_pixel_view_content'      => ['ViewContent',      'Product detail page',        'bg-gray-50  border-gray-200'],
                            'meta_pixel_search'            => ['Search',           'Live & product search',      'bg-gray-50  border-gray-200'],
                            'meta_pixel_add_to_cart'       => ['AddToCart',        'Add to cart click',          'bg-orange-50 border-orange-200'],
                            'meta_pixel_initiate_checkout' => ['InitiateCheckout', 'Checkout page load',         'bg-orange-50 border-orange-200'],
                            'meta_pixel_purchase'          => ['Purchase',         'Order placed ★ most important', 'bg-green-50 border-green-200'],
                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => [$event, $desc, $colors]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $enabled = ($settings[$key] ?? 'true') === 'true'; ?>
                        <label class="flex items-center justify-between p-3.5 border rounded-xl cursor-pointer hover:brightness-95 transition-all <?php echo e($colors); ?>">
                            <div>
                                <p class="text-sm font-bold text-gray-800 font-mono"><?php echo e($event); ?></p>
                                <p class="text-xs text-gray-500 mt-0.5"><?php echo e($desc); ?></p>
                            </div>
                            <div class="relative flex-shrink-0 ml-3">
                                <input type="checkbox" name="<?php echo e($key); ?>" value="true" <?php echo e($enabled ? 'checked' : ''); ?> class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="submit" class="btn-primary"><i class="fas fa-save mr-2"></i>Save Pixel Settings</button>
                </div>
            </form>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['meta_pixel_id'])): ?>
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Pixel Status</p>
            <div class="flex flex-wrap gap-3">
                <div class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl <?php echo e(($settings['meta_pixel_mode'] ?? '') === 'live' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200'); ?>">
                    <span><?php echo e(($settings['meta_pixel_mode'] ?? '') === 'live' ? '🟢' : '🧪'); ?></span>
                    <span class="text-sm font-bold text-gray-800"><?php echo e(strtoupper($settings['meta_pixel_mode'] ?? 'TEST')); ?></span>
                </div>
                <div class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl bg-blue-50 border-blue-200">
                    <span class="text-xs text-gray-500 font-medium">Pixel ID</span>
                    <span class="text-sm font-mono font-bold text-gray-800"><?php echo e($settings['meta_pixel_id']); ?></span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['meta_pixel_test_event_code']) && ($settings['meta_pixel_mode'] ?? '') === 'test'): ?>
                <div class="flex items-center gap-2.5 px-4 py-2.5 border rounded-xl bg-yellow-50 border-yellow-200">
                    <span class="text-xs text-gray-500 font-medium">Test Code</span>
                    <span class="text-sm font-mono font-bold text-gray-800"><?php echo e($settings['meta_pixel_test_event_code']); ?></span>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('[name=meta_pixel_mode]').forEach(r => {
    r.addEventListener('change', () => {
        const wrap = document.getElementById('test-code-wrap');
        if (wrap) wrap.style.display = r.value === 'test' ? 'block' : 'none';
    });
});

function buildCheckoutJson() {
    const fields = {};
    document.querySelectorAll('[name^="checkout_field["]').forEach(el => {
        const match = el.name.match(/checkout_field\[([^\]]+)\]\[([^\]]+)\]/);
        if (!match) return;
        const [, key, prop] = match;
        if (!fields[key]) fields[key] = { visible: false, required: false };
        if (el.checked) fields[key][prop] = true;
    });
    document.getElementById('checkout-fields-json').value = JSON.stringify(fields);
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>