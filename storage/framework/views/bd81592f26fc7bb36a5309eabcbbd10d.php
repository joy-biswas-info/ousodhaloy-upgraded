<?php $__env->startSection('page-title', 'Bulk Add / Import Products'); ?>
<?php $__env->startSection('breadcrumb', 'Products / Bulk Import'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-5" x-data="bulkProducts()">
        
        <div class="bg-white rounded-xl border p-1.5 flex gap-1">
            <button @click="tab='form'"
                :class="tab === 'form' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                <i class="fas fa-table mr-1.5"></i>Spreadsheet Entry
            </button>
            <button @click="tab='csv'"
                :class="tab === 'csv' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                <i class="fas fa-file-csv mr-1.5"></i>CSV Upload
            </button>
        </div>
        
        <div x-show="tab==='form'">
            <form method="POST" action="<?php echo e(route('admin.products.bulk-store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="bg-white rounded-xl border overflow-hidden">
                    <div class="px-5 py-4 border-b flex items-center justify-between">
                        <h2 class="font-bold text-gray-800">Add Multiple Products</h2>
                        <button type="button" @click="addRow()" class="btn-secondary btn-sm">
                            <i class="fas fa-plus mr-1"></i>Add Row
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs" style="min-width:1200px">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold w-8">#</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold" style="min-width:190px">
                                        Name *</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold" style="min-width:130px">
                                        Generic Name</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold" style="min-width:100px">
                                        SKU</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold" style="min-width:120px">
                                        Brand</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold" style="min-width:120px">
                                        Category</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold w-20">Price *</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold w-20">MRP</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold w-18">Stock *</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold w-18">Unit</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold w-20">Strength</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold w-20">Form</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold" style="min-width:120px">
                                        Tags</th>
                                    <th class="px-2 py-2.5 text-left text-gray-500 font-semibold" style="min-width:150px">
                                        Thumbnail</th>
                                    <th class="px-2 py-2.5 text-center text-gray-500 font-semibold w-8">Rx</th>
                                    <th class="px-2 py-2.5 w-8"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, i) in rows" :key="i">
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-2 py-1.5 text-gray-400 text-center text-xs" x-text="i+1"></td>

                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][name]'" x-model="row.name"
                                                class="form-input py-1 text-xs" placeholder="Product name" required>
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][generic_name]'"
                                                x-model="row.generic_name" class="form-input py-1 text-xs"
                                                placeholder="e.g. Paracetamol">
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][sku]'" x-model="row.sku"
                                                class="form-input py-1 text-xs" placeholder="NAP500">
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][brand]'" x-model="row.brand"
                                                list="brand-list" class="form-input py-1 text-xs" placeholder="Brand">
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][category]'"
                                                x-model="row.category" list="category-list" class="form-input py-1 text-xs"
                                                placeholder="Category">
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="number" :name="'products[' + i + '][price]'" x-model="row.price"
                                                step="0.01" min="0" class="form-input py-1 text-xs"
                                                placeholder="0.00" required>
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="number" :name="'products[' + i + '][mrp]'" x-model="row.mrp"
                                                step="0.01" min="0" class="form-input py-1 text-xs"
                                                placeholder="0.00">
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="number" :name="'products[' + i + '][stock]'" x-model="row.stock"
                                                min="0" class="form-input py-1 text-xs" placeholder="0" required>
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <select :name="'products[' + i + '][unit]'" x-model="row.unit"
                                                class="form-select py-1 text-xs">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['pcs', 'strip', 'bottle', 'box', 'pack', 'tube', 'vial', 'sachet', 'ml', 'gm']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($u); ?>"><?php echo e($u); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][strength]'"
                                                x-model="row.strength" class="form-input py-1 text-xs"
                                                placeholder="500mg">
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][form]'" x-model="row.form"
                                                list="form-list" class="form-input py-1 text-xs" placeholder="Tablet">
                                        </td>
                                        <td class="px-1.5 py-1.5">
                                            <input type="text" :name="'products[' + i + '][tags]'" x-model="row.tags"
                                                class="form-input py-1 text-xs" placeholder="fever,pain">
                                        </td>

                                        
                                        <td class="px-1.5 py-1.5">
                                            <input type="hidden" :name="'products[' + i + '][thumbnail]'"
                                                x-model="row.thumbnail">
                                            <div class="flex items-center gap-1.5">
                                                <div class="w-8 h-8 bg-gray-100 rounded border flex-shrink-0 overflow-hidden"
                                                    x-show="row.thumbnailUrl">
                                                    <img :src="row.thumbnailUrl" class="w-full h-full object-cover">
                                                </div>
                                                <div class="w-8 h-8 bg-gray-100 rounded border flex-shrink-0 flex items-center justify-center text-gray-300"
                                                    x-show="!row.thumbnailUrl">
                                                    <i class="fas fa-image text-xs"></i>
                                                </div>
                                                <button type="button" @click="pickRowImage(i)"
                                                    class="btn-secondary btn-sm py-1 px-2 text-xs flex-shrink-0">
                                                    Pick
                                                </button>
                                            </div>
                                        </td>

                                        <td class="px-1.5 py-1.5 text-center">
                                            <input type="checkbox" :name="'products[' + i + '][requires_prescription]'"
                                                value="1" x-model="row.rx" class="accent-teal-600">
                                        </td>
                                        <td class="px-1.5 py-1.5 text-center">
                                            <button type="button" @click="removeRow(i)" x-show="rows.length > 1"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <i class="fas fa-times text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <div class="px-5 py-4 border-t flex items-center justify-between bg-gray-50">
                        <div class="flex gap-2">
                            <button type="button" @click="addRow()" class="btn-outline btn-sm">
                                <i class="fas fa-plus mr-1"></i>Add Row
                            </button>
                            <button type="button" @click="addRows(5)" class="btn-outline btn-sm">+5 Rows</button>
                            <button type="button" @click="clearAll()" class="btn-outline btn-sm text-red-500">Clear
                                All</button>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs text-gray-500" x-text="rows.length + ' rows'"></span>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>Save All Products
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            
            <datalist id="brand-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($b->name); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </datalist>
            <datalist id="category-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($c->name); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </datalist>
            <datalist id="form-list">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Tablet', 'Capsule', 'Syrup', 'Cream', 'Ointment', 'Injection', 'Eye Drop', 'Ear Drop', 'Inhaler', 'Suspension', 'Powder', 'Gel', 'Spray']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($f); ?>">
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </datalist>
        </div>

        
        <div x-show="tab=='csv'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Upload CSV</h2>

                    <form method="POST" action="<?php echo e(route('admin.products.import-csv')); ?>" enctype="multipart/form-data"
                        class="space-y-4">
                        <?php echo csrf_field(); ?>

                        
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center
                                hover:border-teal-400 transition-colors cursor-pointer"
                            onclick="document.getElementById('csv-input').click()">
                            <i class="fas fa-file-csv text-4xl text-gray-300 mb-3 block"></i>
                            <p class="text-sm font-semibold text-gray-600">Click to select CSV file</p>
                            <p class="text-xs text-gray-400 mt-1">Max 10 MB · .csv or .txt</p>
                            <p id="csv-filename" class="text-xs text-teal-600 font-semibold mt-2 truncate"></p>
                        </div>
                        <input type="file" id="csv-input" name="csv_file" accept=".csv,.txt" class="hidden"
                            onchange="document.getElementById('csv-filename').textContent = this.files[0]?.name || ''">

                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-xs text-amber-800 space-y-1">
                            <p class="font-bold">Before uploading:</p>
                            <p>• Row 1 must be the header row (use the template below)</p>
                            <p>• Brand &amp; category are auto-created if they don't exist</p>
                            <p>• Matched by SKU first, then name — existing products are updated</p>
                            <p>• Use <code class="bg-amber-100 px-1 rounded">tab:Description</code> columns for product
                                content tabs</p>
                        </div>

                        <button type="submit" class="btn-primary w-full">
                            <i class="fas fa-upload mr-2"></i>Import CSV
                        </button>
                    </form>

                    
                    <div class="border-t mt-5 pt-5" x-data="{ tabs: 'Description,Usage & Dosage,Side Effects', open: false }">
                        <p class="text-sm font-bold text-gray-700 mb-3">Download CSV Template</p>
                        <div class="space-y-2">
                            <label class="form-label">Content tab columns (comma separated)</label>
                            <input type="text" x-model="tabs" class="form-input"
                                placeholder="Description,Usage & Dosage,Side Effects">
                            <p class="text-xs text-gray-400">These become <code>tab:Description</code> columns in the CSV
                            </p>
                            <a :href="'<?php echo e(route('admin.products.csv-template')); ?>?tabs=' + encodeURIComponent(tabs)"
                                class="btn-secondary w-full text-center">
                                <i class="fas fa-download mr-2"></i>Download Template
                            </a>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Column Reference</h2>
                    <div class="overflow-x-auto">
                        <table class="admin-table text-xs">
                            <thead>
                                <tr>
                                    <th>Column</th>
                                    <th>Required</th>
                                    <th>Example</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [['name', 'Yes', 'Napa 500mg'], ['generic_name', 'No', 'Paracetamol'], ['sku', 'No', 'NAP500'], ['brand', 'No', 'Beximco Pharma'], ['category', 'No', 'Medicine'], ['price', 'Yes', '0.90'], ['mrp', 'No', '1.00'], ['stock', 'Yes', '500'], ['unit', 'No', 'strip'], ['strength', 'No', '500mg'], ['form', 'No', 'Tablet'], ['pack_size', 'No', '10 tablets/strip'], ['requires_prescription', 'No', '0 or 1'], ['is_featured', 'No', '0 or 1'], ['tags', 'No', 'fever,pain'], ['thumbnail', 'No', 'media/napa.jpg'], ['low_stock_alert', 'No', '20'], ['tab:Description', 'No', 'Free text content'], ['tab:Usage & Dosage', 'No', 'Free text content']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$col, $req, $ex]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="font-mono font-bold text-teal-700"><?php echo e($col); ?></td>
                                        <td><span
                                                class="text-xs font-semibold <?php echo e($req === 'Yes' ? 'text-red-500' : 'text-gray-400'); ?>"><?php echo e($req); ?></span>
                                        </td>
                                        <td class="text-gray-500"><?php echo e($ex); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function bulkProducts() {
            return {
                tab: 'form',
                rows: [newBulkRow()],
                _pickerRowIndex: null,

                addRow() {
                    this.rows.push(newBulkRow());
                },
                addRows(n) {
                    for (let i = 0; i < n; i++) this.rows.push(newBulkRow());
                },
                removeRow(i) {
                    if (this.rows.length > 1) this.rows.splice(i, 1);
                },
                clearAll() {
                    if (confirm('Clear all rows?')) this.rows = [newBulkRow()];
                },

                pickRowImage(rowIndex) {
                    this._pickerRowIndex = rowIndex;
                    const self = this;
                    openMediaPicker('bulk-row-' + rowIndex, (path, url) => {
                        self.rows[rowIndex].thumbnail = path;
                        self.rows[rowIndex].thumbnailUrl = url;
                    });
                },
            };
        }

        function newBulkRow() {
            return {
                name: '',
                generic_name: '',
                sku: '',
                brand: '',
                category: '',
                price: '',
                mrp: '',
                stock: '',
                unit: 'strip',
                strength: '',
                form: '',
                tags: '',
                thumbnail: '',
                thumbnailUrl: '',
                rx: false,
            };
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/products/bulk.blade.php ENDPATH**/ ?>