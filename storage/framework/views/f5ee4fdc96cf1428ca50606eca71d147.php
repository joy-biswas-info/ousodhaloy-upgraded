<?php $__env->startSection('page-title', 'Create Manual Order'); ?>
<?php $__env->startSection('breadcrumb', 'Orders / New Manual Order'); ?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e(route('admin.orders.manual-store')); ?>" id="manual-order-form" x-data="manualOrder()">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            
            <div class="xl:col-span-2 space-y-5">

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-pills text-teal-600"></i> Order Items
                    </h2>

                    
                    <div class="relative mb-4">
                        <input type="text" x-model="searchQ" @input.debounce.300ms="searchProducts()"
                            @keydown.escape="searchResults = []" placeholder="Search product by name, generic, SKU..."
                            class="form-input pl-9">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>

                        
                        <div x-show="searchResults.length" @click.away="searchResults = []"
                            class="absolute z-50 bg-white border rounded-xl shadow-xl mt-1 w-full max-h-64 overflow-y-auto">
                            <template x-for="p in searchResults" :key="p . id">
                                <button type="button" @click="addItem(p)"
                                    class="w-full flex items-center gap-3 px-4 py-3 hover:bg-teal-50 text-left border-b last:border-0 transition-colors">
                                    <div
                                        class="w-10 h-10 bg-gray-50 border rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden">
                                        <img x-show="p.thumbnail" :src="p . thumbnail"
                                            class="max-h-full max-w-full object-contain">
                                        <span x-show="!p.thumbnail">💊</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800" x-text="p.name"></p>
                                        <p class="text-xs text-gray-500"
                                            x-text="(p.generic_name || '') + (p.brand ? ' · ' + p.brand : '') + ' · Stock: ' + p.stock + ' ' + p.unit">
                                        </p>
                                    </div>
                                    <div class="text-right flex-shrink-0">
                                        <p class="text-sm font-bold text-teal-700">৳<span x-text="p.price"></span></p>
                                        <p class="text-xs text-gray-400" x-text="'/' + p.unit"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    
                    <div x-show="items.length === 0"
                        class="text-center py-10 text-gray-400 border-2 border-dashed rounded-xl">
                        <i class="fas fa-search text-3xl mb-2"></i>
                        <p class="text-sm">Search and add products above</p>
                    </div>

                    <div x-show="items.length > 0">
                        <table class="admin-table w-full mb-3">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th class="w-24">Qty</th>
                                    <th class="w-32">Unit Price (৳)</th>
                                    <th class="w-24">Subtotal</th>
                                    <th class="w-10"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(item, i) in items" :key="i">
                                    <tr>
                                        <td>
                                            <input type="hidden" :name="'items[' + i + '][product_id]'" :value="item . id">
                                            <p class="font-semibold text-sm text-gray-800" x-text="item.name"></p>
                                            <p class="text-xs text-gray-400" x-text="item.generic_name"></p>
                                        </td>
                                        <td>
                                            <input type="number" :name="'items[' + i + '][qty]'" x-model.number="item.qty"
                                                @change="calcTotals()" min="1" :max="item . stock"
                                                class="form-input text-center py-1.5 px-2" style="width:70px">
                                        </td>
                                        <td>
                                            <div class="relative">
                                                <span
                                                    class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs">৳</span>
                                                <input type="number" :name="'items[' + i + '][price]'"
                                                    x-model.number="item.price" @change="calcTotals()" step="0.01" min="0"
                                                    class="form-input py-1.5 pl-6" style="width:110px">
                                            </div>
                                        </td>
                                        <td class="font-bold text-teal-700 text-sm">
                                            ৳<span x-text="(item.qty * item.price).toFixed(2)"></span>
                                        </td>
                                        <td>
                                            <button type="button" @click="removeItem(i)"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-user text-teal-600"></i> Customer & Delivery
                    </h2>

                    
                    <div class="mb-4">
                        <label class="form-label">Link to Existing Customer (optional)</label>
                        <select name="user_id" class="form-select" onchange="fillCustomer(this)">
                            <option value="">Guest / New Customer</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($c->id); ?>" data-phone="<?php echo e($c->phone); ?>" data-name="<?php echo e($c->name); ?>">
                                    <?php echo e($c->name); ?> — <?php echo e($c->phone); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="shipping_name" id="f-name" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Phone *</label>
                            <input type="text" name="shipping_phone" id="f-phone" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="shipping_email" class="form-input" placeholder="Optional">
                        </div>
                        <div>
                            <label class="form-label">Division *</label>
                            <select name="shipping_division" class="form-select" onchange="updateDistricts(this.value)"
                                required>
                                <option value="">Select division</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $divisions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $div): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($div); ?>"><?php echo e($div); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">District *</label>
                            <select name="shipping_district" id="district-select" class="form-select" required>
                                <option value="">Select district</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Upazila</label>
                            <input type="text" name="shipping_upazila" class="form-input">
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Full Address *</label>
                            <textarea name="shipping_address" rows="2" class="form-input resize-none" required></textarea>
                        </div>
                        <div>
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="shipping_postcode" class="form-input" maxlength="10">
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-sticky-note text-teal-600"></i> Notes
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Customer Note</label>
                            <textarea name="customer_note" rows="2" class="form-input resize-none"
                                placeholder="Visible to customer"></textarea>
                        </div>
                        <div>
                            <label class="form-label">Admin Note</label>
                            <textarea name="admin_note" rows="2" class="form-input resize-none"
                                placeholder="Internal only"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-4">

                
                <div class="bg-white rounded-xl border p-5 space-y-3">
                    <button type="submit" class="btn-primary w-full py-3 text-base">
                        <i class="fas fa-check-circle mr-2"></i>Create Order
                    </button>
                    <a href="<?php echo e(route('admin.orders.index')); ?>"
                        class="btn-outline w-full py-2.5 text-center block">Cancel</a>
                </div>

                
                <div class="bg-white rounded-xl border p-5 space-y-3">
                    <h3 class="font-bold text-gray-800 text-sm pb-2 border-b">Payment & Status</h3>
                    <div>
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-select">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Order::STATUS_LABELS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php if($key === 'confirmed'): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['cod' => 'Cash on Delivery', 'bkash' => 'bKash', 'nagad' => 'Nagad', 'ssl_commerz' => 'Card / Online', 'bank' => 'Bank Transfer']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Promo Code</label>
                        <input type="text" name="promo_code" class="form-input uppercase" placeholder="Optional">
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5 space-y-3">
                    <h3 class="font-bold text-gray-800 text-sm pb-2 border-b">Pricing</h3>
                    <div>
                        <label class="form-label">Delivery Charge (৳)</label>
                        <input type="number" name="delivery_charge" x-model.number="delivery" @input="calcTotals()"
                            step="0.01" min="0" value="60" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Manual Discount (৳)</label>
                        <input type="number" name="discount" x-model.number="discount" @input="calcTotals()" step="0.01"
                            min="0" value="0" class="form-input">
                    </div>

                    
                    <div class="border-t pt-3 space-y-1.5 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>
                            <span>৳<span x-text="subtotal.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Delivery</span>
                            <span>৳<span x-text="parseFloat(delivery||0).toFixed(2)"></span></span>
                        </div>
                        <div x-show="discount > 0" class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span>−৳<span x-text="parseFloat(discount||0).toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between font-black text-base border-t pt-2">
                            <span>Total</span>
                            <span class="text-teal-700">৳<span x-text="total.toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5 space-y-2">
                    <h3 class="font-bold text-gray-800 text-sm pb-2 border-b">Options</h3>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="deduct_stock" value="1" checked class="accent-teal-600">
                        <span class="text-sm text-gray-700">Deduct stock on save</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="send_sms" value="1" class="accent-teal-600">
                        <span class="text-sm text-gray-700">Send order confirmation SMS</span>
                    </label>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        const BD_DISTRICTS = <?php echo json_encode(config('bd.districts'), 15, 512) ?>;

        function manualOrder() {
            return {
                searchQ: '',
                searchResults: [],
                items: [],
                subtotal: 0,
                delivery: 60,
                discount: 0,
                total: 60,

                async searchProducts() {
                    if (this.searchQ.length < 2) { this.searchResults = []; return; }
                    const res = await fetch('/admin/orders/product-search?q=' + encodeURIComponent(this.searchQ), {
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
                    });
                    this.searchResults = await res.json();
                },

                addItem(product) {
                    const existing = this.items.find(i => i.id === product.id);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.items.push({ ...product, qty: 1 });
                    }
                    this.searchQ = '';
                    this.searchResults = [];
                    this.calcTotals();
                },

                removeItem(index) {
                    this.items.splice(index, 1);
                    this.calcTotals();
                },

                calcTotals() {
                    this.subtotal = this.items.reduce((sum, i) => sum + (i.qty * i.price), 0);
                    this.total = Math.max(0, this.subtotal + parseFloat(this.delivery || 0) - parseFloat(this.discount || 0));
                }
            };
        }

        function updateDistricts(division) {
            const sel = document.getElementById('district-select');
            sel.innerHTML = '<option value="">Select district</option>';
            (BD_DISTRICTS[division] || []).forEach(d => {
                const o = document.createElement('option');
                o.value = d; o.textContent = d;
                sel.appendChild(o);
            });
        }

        function fillCustomer(select) {
            const opt = select.options[select.selectedIndex];
            if (opt.value) {
                document.getElementById('f-name').value = opt.dataset.name || '';
                document.getElementById('f-phone').value = opt.dataset.phone || '';
            }
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/orders/create.blade.php ENDPATH**/ ?>