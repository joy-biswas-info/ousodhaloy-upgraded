<?php $__env->startSection('title', $product ? 'Edit: ' . $product->name : 'Add Product'); ?>
<?php $__env->startSection('page-title', $product ? 'Edit Product' : 'Add New Product'); ?>
<?php $__env->startSection('breadcrumb', 'Products / ' . ($product ? 'Edit' : 'New')); ?>


<?php
    $defaultTabs = [
        ['id' => 'desc', 'label' => 'Description', 'content' => ''],
        ['id' => 'usage', 'label' => 'Usage & Dosage', 'content' => ''],
        ['id' => 'info', 'label' => 'Product Info', 'content' => ''],
    ];
    $tabs = old('tabs') ? json_decode(old('tabs'), true) : $product?->tabs ?? $defaultTabs;

    // Safely get selected category IDs
    $selectedCatIds = old(
        'category_ids',
        (function () use ($product) {
            if (!$product) {
                return [];
            }
            try {
                $cats = $product->relationLoaded('categories') ? $product->categories : $product->categories()->get();
                return $cats instanceof \Illuminate\Support\Collection
                    ? $cats->pluck('id')->toArray()
                    : ($product->category_id
                        ? [$product->category_id]
                        : []);
            } catch (\Throwable $e) {
                return $product->category_id ? [$product->category_id] : [];
            }
        })(),
    );
?>

<?php $__env->startSection('content'); ?>
    <form method="POST" action="<?php echo e($product ? route('admin.products.update', $product) : route('admin.products.store')); ?>"
        enctype="multipart/form-data" id="product-form" x-data="productForm()">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product): ?>
            <?php echo method_field('PUT'); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

            
            <div class="xl:col-span-2 space-y-5">

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-info-circle text-teal-600"></i> Basic Information
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="form-label">Product Name *</label>
                            <input type="text" name="name" value="<?php echo e(old('name', $product?->name)); ?>"
                                @input="if(!slugEdited) slug = slugify($event.target.value)" class="form-input"
                                placeholder="e.g. Napa 500" required>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="form-label">URL Slug *</label>
                            <input type="text" name="slug" x-model="slug" @input="slugEdited = true"
                                class="form-input" placeholder="napa-500">
                        </div>
                        <div>
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" value="<?php echo e(old('sku', $product?->sku)); ?>" class="form-input"
                                placeholder="NAP500">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="form-error"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="form-label">Generic Name / INN</label>
                            <input type="text" name="generic_name"
                                value="<?php echo e(old('generic_name', $product?->generic_name)); ?>" class="form-input"
                                placeholder="Paracetamol">
                        </div>
                        <div>
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode" value="<?php echo e(old('barcode', $product?->barcode)); ?>"
                                class="form-input" placeholder="Optional">
                        </div>
                        <div>
                            <label class="form-label">Brand / Manufacturer</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Select brand</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($brand->id); ?>" <?php if(old('brand_id', $product?->brand_id) == $brand->id): echo 'selected'; endif; ?>><?php echo e($brand->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Primary Category</label>
                            <select name="category_id" class="form-select">
                                <option value="">Select primary category</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php if(old('category_id', $product?->category_id) == $cat->id): echo 'selected'; endif; ?>><?php echo e($cat->icon); ?>

                                        <?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Used for breadcrumbs & main filter</p>
                        </div>
                        <div class="col-span-2">
                            <label class="form-label">Additional Categories
                                <span class="font-normal text-gray-400 normal-case">(hold Ctrl/Cmd to select
                                    multiple)</span>
                            </label>
                            <select name="category_ids[]" multiple class="form-select" style="height:110px">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->id); ?>" <?php if(in_array($cat->id, (array) $selectedCatIds)): echo 'selected'; endif; ?>>
                                        <?php echo e($cat->icon); ?> <?php echo e($cat->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Product appears in all selected categories</p>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-tag text-teal-600"></i> Pricing & Stock
                    </h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div>

                            <label class="form-label">Sale Price (৳) *</label>

                            <input type="number" id="price" name="price" step="0.01"
                                value="<?php echo e(old('price', $product?->price)); ?>" class="form-input" required
                                placeholder="10.80">

                        </div>

                        <div>

                            <label class="form-label">MRP / Unit Price (৳)</label>

                            <input type="number" id="mrp" name="mrp" step="0.01"
                                value="<?php echo e(old('mrp', $product?->mrp)); ?>" class="form-input" placeholder="12.00">

                        </div>

                        <div>

                            <label class="form-label">Discount %</label>

                            <input type="number" id="discount_percent" name="discount_percent" step="0.01"
                                max="100" value="<?php echo e(old('discount_percent', $product?->discount_percent ?? 0)); ?>"
                                class="form-input" placeholder="10" readonly>

                        </div>
                        <div>
                            <label class="form-label">Stock Quantity *</label>
                            <input type="number" name="stock" value="<?php echo e(old('stock', $product?->stock ?? 0)); ?>"
                                class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label">Low Stock Alert At</label>
                            <input type="number" name="low_stock_alert"
                                value="<?php echo e(old('low_stock_alert', $product?->low_stock_alert ?? 10)); ?>"
                                class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Unit</label>
                            <select name="unit" class="form-select">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['pcs', 'strip', 'bottle', 'box', 'pack', 'tube', 'vial', 'sachet', 'ml', 'mg', 'gm']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u); ?>" <?php if(old('unit', $product?->unit ?? 'pcs') === $u): echo 'selected'; endif; ?>><?php echo e($u); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Min Order Qty</label>
                            <input type="number" name="min_order_qty"
                                value="<?php echo e(old('min_order_qty', $product?->min_order_qty ?? 1)); ?>" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Max Order Qty</label>
                            <input type="number" name="max_order_qty"
                                value="<?php echo e(old('max_order_qty', $product?->max_order_qty ?? 100)); ?>" class="form-input">
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-pills text-teal-600"></i> Medicine Details
                    </h2>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="form-label">Strength</label>
                            <input type="text" name="strength" value="<?php echo e(old('strength', $product?->strength)); ?>"
                                class="form-input" placeholder="500mg">
                        </div>
                        <div>
                            <label class="form-label">Dosage Form</label>
                            <input type="text" name="form" value="<?php echo e(old('form', $product?->form)); ?>"
                                class="form-input" placeholder="Tablet, Syrup, Cream...">
                        </div>
                        <div>
                            <label class="form-label">Pack Size</label>
                            <input type="text" name="pack_size" value="<?php echo e(old('pack_size', $product?->pack_size)); ?>"
                                class="form-input" placeholder="10 tablets/strip">
                        </div>
                        <div class="col-span-3">
                            <label class="form-label">Tags (comma separated)</label>
                            <input type="text" name="tags"
                                value="<?php echo e(old('tags', $product ? implode(', ', $product->tags ?? []) : '')); ?>"
                                class="form-input" placeholder="fever, pain, paracetamol">
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5" x-show="isFlashSale" x-cloak>
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-bolt text-orange-500"></i> Flash Sale Settings
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Flash Sale Price (৳)</label>
                            <input type="number" name="flash_sale_price" step="0.01"
                                value="<?php echo e(old('flash_sale_price', $product?->flash_sale_price)); ?>" class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Flash Sale Ends At</label>
                            <input type="datetime-local" name="flash_sale_ends_at"
                                value="<?php echo e(old('flash_sale_ends_at', $product?->flash_sale_ends_at?->format('Y-m-d\TH:i'))); ?>"
                                class="form-input">
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <div class="flex items-center justify-between mb-4 pb-2 border-b">
                        <h2 class="font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-file-alt text-teal-600"></i> Content Tabs
                        </h2>
                        <button type="button" @click="addTab()" class="btn-secondary text-xs px-3 py-1.5">
                            <i class="fas fa-plus mr-1"></i> Add Tab
                        </button>
                    </div>
                    <div class="flex gap-1.5 flex-wrap mb-4">
                        <template x-for="(tab, i) in tabs" :key="tab.id">
                            <div class="flex items-center">
                                <button type="button" @click="activeTab = i"
                                    :class="activeTab === i ? 'bg-teal-600 text-white' :
                                        'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    class="px-3 py-1.5 text-xs font-semibold rounded-l-lg transition-colors"
                                    x-text="tab.label">
                                </button>
                                <button type="button" @click="removeTab(i)" x-show="tabs.length > 1"
                                    class="bg-red-100 text-red-500 hover:bg-red-200 px-1.5 py-1.5 rounded-r-lg text-xs border-l border-red-200 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    <template x-for="(tab, i) in tabs" :key="tab.id + '-editor'">
                        <div x-show="activeTab === i" class="space-y-3">
                            <div>
                                <label class="form-label">Tab Label</label>
                                <input type="text" x-model="tab.label" class="form-input max-w-xs"
                                    placeholder="Description">
                            </div>
                            <div>
                                <label class="form-label">Content</label>
                                <div class="flex flex-wrap gap-1 p-2 border border-b-0 rounded-t-lg bg-gray-50">
                                    <button type="button" @click="exec('bold')" class="editor-btn"><b>B</b></button>
                                    <button type="button" @click="exec('italic')" class="editor-btn"><i>I</i></button>
                                    <button type="button" @click="exec('underline')"
                                        class="editor-btn"><u>U</u></button>
                                    <span class="w-px h-5 bg-gray-300 mx-0.5 self-center"></span>
                                    <button type="button" @click="execBlock('H2')" class="editor-btn">H2</button>
                                    <button type="button" @click="execBlock('H3')" class="editor-btn">H3</button>
                                    <span class="w-px h-5 bg-gray-300 mx-0.5 self-center"></span>
                                    <button type="button" @click="exec('insertUnorderedList')" class="editor-btn">•
                                        List</button>
                                    <button type="button" @click="exec('insertOrderedList')" class="editor-btn">1.
                                        List</button>
                                    <span class="w-px h-5 bg-gray-300 mx-0.5 self-center"></span>
                                    <button type="button" @click="insertTable()" class="editor-btn">Table</button>
                                    <button type="button" @click="insertLink()" class="editor-btn">🔗</button>
                                </div>
                                <div :id="'editor-' + tab.id" contenteditable="true"
                                    class="border rounded-b-lg p-3 min-h-[180px] text-sm outline-none focus:ring-2 focus:ring-teal-500 prose max-w-none bg-white"
                                    @input="tab.content = $event.target.innerHTML" x-init="$el.innerHTML = tab.content || ''">
                                </div>
                            </div>
                        </div>
                    </template>
                    <input type="hidden" name="tabs" :value="JSON.stringify(tabs)">
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b flex items-center gap-2">
                        <i class="fas fa-search text-teal-600"></i> SEO (Optional)
                    </h2>
                    <div class="space-y-3">
                        <div>
                            <label class="form-label">Meta Title</label>
                            <input type="text" name="meta_title"
                                value="<?php echo e(old('meta_title', $product?->meta_title)); ?>" class="form-input"
                                placeholder="Leave blank to use product name">
                        </div>
                        <div>
                            <label class="form-label">Meta Description</label>
                            <textarea name="meta_description" rows="2" class="form-input resize-none"
                                placeholder="Leave blank to auto-generate"><?php echo e(old('meta_description', $product?->meta_description)); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="space-y-5">

                
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-3">
                        <i class="fas fa-truck text-teal-600 mr-1.5"></i>Custom Delivery Charge
                    </h3>
                    <p class="text-xs text-gray-400 mb-3">
                        Leave blank to use the global delivery charge from Settings. Set a value to override it for this
                        product only.
                    </p>
                    <div class="space-y-3">
                        <div>
                            <label class="form-label">Custom Charge (৳)</label>
                            <input type="number" name="custom_delivery_charge" step="0.01" min="0"
                                value="<?php echo e(old('custom_delivery_charge', $product?->custom_delivery_charge)); ?>"
                                class="form-input" placeholder="e.g. 120 — leave blank for global rate">
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="delivery_charge_per_unit" value="1"
                                    <?php echo e(old('delivery_charge_per_unit', $product?->delivery_charge_per_unit ?? false) ? 'checked' : ''); ?>

                                    class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 peer-checked:after:translate-x-5 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all">
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-700">Multiply by quantity</span>
                                <p class="text-xs text-gray-400">If on: charge × qty. If off: flat charge regardless of
                                    quantity.</p>
                            </div>
                        </label>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-2.5 text-xs text-amber-700">
                            <p class="font-semibold mb-1">How it works:</p>
                            <p>• If blank → global delivery charge applies</p>
                            <p>• If set to <strong>0</strong> → free delivery for this product</p>
                            <p>• If set to <strong>120</strong> → ৳120 regardless of zone</p>
                            <p>• When cart has mixed products, charges are summed</p>
                        </div>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5 space-y-3">
                    <button type="submit" class="w-full btn-primary py-3 text-base">
                        <i class="fas fa-save mr-2"></i><?php echo e($product ? 'Update Product' : 'Create Product'); ?>

                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product): ?>
                        <a href="<?php echo e(route('shop.product', $product->slug)); ?>" target="_blank"
                            class="w-full btn-secondary py-2.5 text-sm text-center block">
                            <i class="fas fa-external-link-alt mr-1"></i> View on Store
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <a href="<?php echo e(route('admin.products.index')); ?>"
                        class="w-full btn-outline py-2.5 text-sm text-center block">Cancel</a>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-3">Thumbnail</h3>
                    <div id="thumb-preview"
                        class="w-full h-44 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden mb-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product?->thumbnail): ?>
                            <img src="<?php echo e($product->thumbnail_url); ?>" class="w-full h-full object-contain">
                        <?php else: ?>
                            <div class="text-center text-gray-400">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                <p class="text-xs">Pick from media library</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <input type="hidden" name="thumbnail_media_path" id="thumbnail-media-path"
                        value="<?php echo e($product?->thumbnail); ?>">
                    <button type="button"
                        onclick="openMediaPicker('thumbnail', (path, url) => {
                        document.getElementById('thumbnail-media-path').value = path;
                        const p = document.getElementById('thumb-preview');
                        p.innerHTML = '<img src=\''+url+'\' class=\'w-full h-full object-contain\'>';
                    })"
                        class="w-full btn-secondary py-2 text-sm">
                        <i class="fas fa-images mr-2"></i>Pick from Media Library
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product?->thumbnail): ?>
                        <button type="button" onclick="clearThumb()"
                            class="w-full btn-outline py-2 text-sm mt-2 text-red-500">
                            <i class="fas fa-times mr-1"></i>Remove thumbnail
                        </button>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <p class="text-xs text-gray-400 mt-2 text-center">
                        Upload images in <a href="<?php echo e(route('admin.media.index')); ?>" target="_blank"
                            class="text-teal-600 underline">Media Library</a> first
                    </p>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-3">Gallery Images</h3>
                    <div id="gallery-wrap" class="flex flex-wrap gap-2 mb-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $product?->images ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $path): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="relative w-20 h-20 bg-gray-50 rounded-lg overflow-hidden border"
                                data-path="<?php echo e($path); ?>">
                                <img src="<?php echo e(asset('storage/' . $path)); ?>" class="w-full h-full object-contain">
                                <button type="button" onclick="removeGalleryItem(this)"
                                    class="absolute top-0 right-0 bg-red-500 text-white w-5 h-5 text-xs flex items-center justify-center rounded-bl">×</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button type="button"
                            onclick="openMediaPicker('gallery', (path, url) => {
                            if (!galleryPaths.includes(path)) {
                                galleryPaths.push(path);
                                document.getElementById('images-json').value = JSON.stringify(galleryPaths);
                                const wrap = document.getElementById('gallery-wrap');
                                const div  = document.createElement('div');
                                div.className = 'relative w-20 h-20 bg-gray-50 rounded-lg overflow-hidden border';
                                div.dataset.path = path;
                                div.innerHTML = '<img src=\''+url+'\' class=\'w-full h-full object-contain\'><button type=\'button\' onclick=\'removeGalleryItem(this)\' class=\'absolute top-0 right-0 bg-red-500 text-white w-5 h-5 text-xs flex items-center justify-center rounded-bl\'>×</button>';
                                wrap.insertBefore(div, wrap.lastElementChild);
                            }
                        })"
                            class="w-20 h-20 border-2 border-dashed border-gray-300 rounded-lg flex flex-col items-center justify-center text-gray-400 hover:border-teal-400 hover:text-teal-600 transition-colors text-xs cursor-pointer">
                            <i class="fas fa-plus text-xl mb-1"></i> Add
                        </button>
                    </div>
                    <input type="hidden" name="images_json" id="images-json"
                        value="<?php echo e(json_encode($product?->images ?? [])); ?>">
                    <p class="text-xs text-gray-400">Pick images already uploaded in Media Library</p>
                </div>

                
                <div class="bg-white rounded-xl border p-5 space-y-3">
                    <h3 class="font-bold text-gray-800 mb-3">Product Status</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [['name' => 'is_active', 'label' => '✅ Active (visible in store)', 'default' => true], ['name' => 'is_featured', 'label' => '⭐ Featured on homepage', 'default' => false], ['name' => 'is_flash_sale', 'label' => '⚡ Flash Sale', 'default' => false, 'toggle' => 'isFlashSale'], ['name' => 'express_delivery', 'label' => '🚀 Express Delivery available', 'default' => false], ['name' => 'requires_prescription', 'label' => '💊 Prescription Required (Rx)', 'default' => false]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $toggle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="flex items-center justify-between py-1 cursor-pointer">
                            <span class="text-sm text-gray-700"><?php echo e($toggle['label']); ?></span>
                            <div class="relative">
                                <input type="checkbox" name="<?php echo e($toggle['name']); ?>" value="1"
                                    <?php echo e(old($toggle['name'], $product?->{$toggle['name']} ?? $toggle['default']) ? 'checked' : ''); ?>

                                    <?php if(isset($toggle['toggle'])): ?> x-model="<?php echo e($toggle['toggle']); ?>" <?php endif; ?>
                                    class="sr-only peer">
                                <div
                                    class="w-10 h-5 bg-gray-200 rounded-full peer peer-checked:bg-teal-600 peer-checked:after:translate-x-5 after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all">
                                </div>
                            </div>
                        </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </form>

    <?php echo $__env->make('partials.media-picker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        function productForm() {
            return {
                slug: '<?php echo e(old(
                    '
                            slug ',
                    $product?->slug ??
                        '
                            ',
                )); ?>',
                slugEdited: {
                    {
                        $product ? 'true' : 'false'
                    }
                },
                isFlashSale: {
                    {
                        old('is_flash_sale', $product ? - > is_flash_sale ?? false) ? 'true' : 'false'
                    }
                },
                activeTab: 0,
                tabs: <?php echo json_encode($tabs, 15, 512) ?>,

                slugify(text) {
                    return text.toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                },
                addTab() {
                    this.tabs.push({
                        id: 'tab_' + Date.now(),
                        label: 'New Tab',
                        content: ''
                    });
                    this.activeTab = this.tabs.length - 1;
                },
                removeTab(i) {
                    if (this.tabs.length <= 1) return;
                    this.tabs.splice(i, 1);
                    if (this.activeTab >= this.tabs.length) this.activeTab = this.tabs.length - 1;
                },
                exec(cmd) {
                    document.execCommand(cmd, false, null);
                },
                execBlock(tag) {
                    document.execCommand('formatBlock', false, tag);
                },
                insertTable() {
                    document.execCommand('insertHTML', false,
                        '<table border="1"><thead><tr><th>Header 1</th><th>Header 2</th></tr></thead><tbody><tr><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table>'
                        );
                },
                insertLink() {
                    const url = prompt('Enter URL:');
                    if (url) document.execCommand('createLink', false, url);
                },
            };
        }

        // ── Thumbnail picker ───────────────────────────────────────────────────────
        function openThumbPicker() {
            openMediaPicker({
                title: 'Pick Thumbnail',
                multiple: false,
                onPick(media) {
                    document.getElementById('thumbnail-media-path').value = media.path;
                    const p = document.getElementById('thumb-preview');
                    p.innerHTML = `<img src="${media.url}" style="width:100%;height:100%;object-fit:contain">`;
                }
            });
        }

        function clearThumb() {
            document.getElementById('thumbnail-media-path').value = '';
            document.getElementById('thumb-preview').innerHTML =
                '<div style="text-align:center;color:#9ca3af"><i class="fas fa-image" style="font-size:2rem;display:block;margin-bottom:6px"></i><p style="font-size:12px">Pick from media library</p></div>';
        }

        // ── Gallery picker ──────────────────────────────────────────────────────────
        let galleryPaths = JSON.parse(document.getElementById('images-json').value || '[]');

        function openGalleryPicker() {
            openMediaPicker({
                title: 'Add Gallery Images',
                multiple: true,
                onPick(media) {
                    if (!galleryPaths.includes(media.path)) {
                        galleryPaths.push(media.path);
                        document.getElementById('images-json').value = JSON.stringify(galleryPaths);
                        const wrap = document.getElementById('gallery-wrap');
                        const div = document.createElement('div');
                        div.className = 'relative w-20 h-20 bg-gray-50 rounded-lg overflow-hidden border';
                        div.dataset.path = media.path;
                        div.innerHTML =
                            `<img src="${media.url}" class="w-full h-full object-contain">
                    <button type="button" onclick="removeGalleryItem(this)"
                        class="absolute top-0 right-0 bg-red-500 text-white w-5 h-5 text-xs flex items-center justify-center rounded-bl">×</button>`;
                        wrap.insertBefore(div, wrap.lastElementChild);
                    }
                }
            });
        }

        function removeGalleryItem(btn) {
            const div = btn.parentElement;
            const path = div.dataset.path;
            galleryPaths = galleryPaths.filter(p => p !== path);
            document.getElementById('images-json').value = JSON.stringify(galleryPaths);
            div.remove();
        }

        function calculateDiscount() {

            const price = parseFloat(document.getElementById('price').value) || 0;

            const mrp = parseFloat(document.getElementById('mrp').value) || 0;

            let discount = 0;

            if (mrp > 0 && price < mrp) {

                discount = ((mrp - price) / mrp) * 100;

            }

            document.getElementById('discount_percent').value = discount.toFixed(2);

        }

        document.getElementById('price').addEventListener('input', calculateDiscount);

        document.getElementById('mrp').addEventListener('input', calculateDiscount);

        calculateDiscount();
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/products/form.blade.php ENDPATH**/ ?>