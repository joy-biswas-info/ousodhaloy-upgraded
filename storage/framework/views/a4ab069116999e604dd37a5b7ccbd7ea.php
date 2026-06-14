<?php $__env->startSection('page-title', 'Brands'); ?>
<?php echo $__env->make('partials.media-picker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    
    <div class="lg:col-span-2 bg-white rounded-xl border overflow-hidden">
        <div class="px-5 py-4 border-b flex items-center justify-between">
            <h2 class="font-bold text-gray-800">Brands <span class="text-gray-400 font-normal text-sm ml-1">(<?php echo e($brands->total()); ?>)</span></h2>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Brand</th>
                    <th>Country</th>
                    <th class="text-center">Products</th>
                    <th class="text-center">Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr x-data="{ editing: false }">

                    
                    <td x-show="!editing">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gray-50 border flex items-center justify-center overflow-hidden flex-shrink-0">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($brand->logo): ?>
                                    <img src="<?php echo e($brand->logo_url); ?>" class="max-h-full max-w-full object-contain">
                                <?php else: ?>
                                    <i class="fas fa-flask text-gray-300 text-sm"></i>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-gray-800"><?php echo e($brand->name); ?></p>
                                <p class="text-xs text-gray-400 font-mono"><?php echo e($brand->slug); ?></p>
                            </div>
                        </div>
                    </td>

                    
                    <td colspan="3" x-show="editing" class="py-3 pr-3">
                        <form method="POST" action="<?php echo e(route('admin.brands.update', $brand)); ?>" class="flex flex-wrap gap-2 items-end" id="edit-form-<?php echo e($brand->id); ?>">
                            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                            <input type="hidden" name="logo_media_path" id="logo-path-<?php echo e($brand->id); ?>" value="<?php echo e($brand->logo); ?>">

                            
                            <div class="flex-shrink-0">
                                <p class="text-xs text-gray-500 mb-1">Logo</p>
                                <div class="relative w-12 h-12 rounded-lg bg-gray-50 border overflow-hidden cursor-pointer group"
                                     onclick="pickBrandLogo(<?php echo e($brand->id); ?>)">
                                    <img id="logo-preview-<?php echo e($brand->id); ?>"
                                         src="<?php echo e($brand->logo_url ?: ''); ?>"
                                         class="w-full h-full object-contain <?php echo e($brand->logo ? '' : 'hidden'); ?>">
                                    <div id="logo-placeholder-<?php echo e($brand->id); ?>" class="<?php echo e($brand->logo ? 'hidden' : ''); ?> w-full h-full flex items-center justify-center">
                                        <i class="fas fa-flask text-gray-300 text-sm"></i>
                                    </div>
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <i class="fas fa-camera text-white text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Name</label>
                                <input type="text" name="name" value="<?php echo e($brand->name); ?>" class="form-input" style="width:160px" required>
                            </div>
                            <div>
                                <label class="text-xs text-gray-500 mb-1 block">Country</label>
                                <input type="text" name="country" value="<?php echo e($brand->country); ?>" class="form-input" style="width:110px" placeholder="Bangladesh">
                            </div>
                            <div class="flex gap-1.5 items-end">
                                <button type="submit" class="btn-primary btn-sm">Save</button>
                                <button type="button" @click="editing = false" class="btn-outline btn-sm">Cancel</button>
                            </div>
                        </form>
                    </td>

                    <td x-show="!editing" class="text-sm text-gray-500"><?php echo e($brand->country ?? '—'); ?></td>

                    <td x-show="!editing" class="text-center text-sm text-gray-600"><?php echo e($brand->products_count); ?></td>

                    <td x-show="!editing" class="text-center">
                        <span class="text-xs font-semibold <?php echo e($brand->is_active ? 'text-green-600' : 'text-gray-400'); ?>">
                            <?php echo e($brand->is_active ? 'Active' : 'Hidden'); ?>

                        </span>
                    </td>

                    <td class="text-right" x-show="!editing">
                        <div class="flex gap-1.5 justify-end">
                            <button @click="editing = true" class="btn-secondary btn-sm">Edit</button>

                            <form method="POST" action="<?php echo e(route('admin.brands.update', $brand)); ?>" x-ref="toggleForm" class="hidden">
                                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                                <input type="hidden" name="name" value="<?php echo e($brand->name); ?>">
                                <input type="hidden" name="country" value="<?php echo e($brand->country); ?>">
                                <input type="hidden" name="is_active" value="<?php echo e($brand->is_active ? 0 : 1); ?>">
                            </form>
                            <button @click="$refs.toggleForm.submit()" class="btn-outline btn-sm">
                                <?php echo e($brand->is_active ? 'Hide' : 'Show'); ?>

                            </button>

                            <form method="POST" action="<?php echo e(route('admin.brands.destroy', $brand)); ?>"
                                  onsubmit="return confirm('Deactivate <?php echo e(addslashes($brand->name)); ?>?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>

                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center py-12 text-gray-400">
                        <i class="fas fa-flask text-3xl mb-2 block"></i>
                        No brands yet — add one →
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <div class="p-4 border-t"><?php echo e($brands->links()); ?></div>
    </div>

    
    <div class="space-y-5">
        <div class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Brand</h2>
            <form method="POST" action="<?php echo e(route('admin.brands.store')); ?>" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="logo_media_path" id="new-logo-path">

                
                <div>
                    <label class="form-label">Logo</label>
                    <div class="flex items-center gap-3">
                        <div class="w-14 h-14 rounded-xl bg-gray-50 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden flex-shrink-0 cursor-pointer hover:border-teal-400 transition-colors"
                             onclick="pickNewBrandLogo()" id="new-logo-preview-wrap">
                            <img id="new-logo-preview" src="" class="w-full h-full object-contain hidden">
                            <i class="fas fa-flask text-gray-300 text-xl" id="new-logo-placeholder"></i>
                        </div>
                        <button type="button" onclick="pickNewBrandLogo()" class="btn-outline text-sm py-2 px-3">
                            <i class="fas fa-images mr-1.5"></i>Pick from library
                        </button>
                    </div>
                </div>

                <div>
                    <label class="form-label">Brand Name *</label>
                    <input type="text" name="name" class="form-input" placeholder="Square Pharmaceuticals" required>
                </div>
                <div>
                    <label class="form-label">Country</label>
                    <input type="text" name="country" value="Bangladesh" class="form-input">
                </div>
                <button type="submit" class="btn-primary w-full">Add Brand</button>
            </form>
        </div>

        
        <div class="bg-white rounded-xl border p-5">
            <p class="text-xs font-semibold text-gray-500 mb-3 uppercase tracking-wide">🇧🇩 Top BD Pharma</p>
            <div class="space-y-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['Square Pharmaceuticals','Beximco Pharma','Renata Limited','ACI Limited','Incepta Pharma','Drug International','Aristopharma','Eskayef','Opsonin Pharma','Globe Pharma']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <form method="POST" action="<?php echo e(route('admin.brands.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="name" value="<?php echo e($b); ?>">
                    <input type="hidden" name="country" value="Bangladesh">
                    <button type="submit" class="w-full text-left text-xs text-gray-600 hover:text-teal-700 hover:bg-teal-50 px-2 py-1.5 rounded-lg transition-colors flex items-center justify-between group">
                        <span><?php echo e($b); ?></span>
                        <i class="fas fa-plus text-gray-300 group-hover:text-teal-500 text-xs"></i>
                    </button>
                </form>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

</div>

<script>
// New brand logo picker
function pickNewBrandLogo() {
    openMediaPicker('new-brand-logo', function(path, url) {
        document.getElementById('new-logo-path').value = path;
        document.getElementById('new-logo-preview').src = url;
        document.getElementById('new-logo-preview').classList.remove('hidden');
        document.getElementById('new-logo-placeholder').classList.add('hidden');
    });
}

// Existing brand logo picker (in edit row)
function pickBrandLogo(id) {
    openMediaPicker('brand-logo-' + id, function(path, url) {
        document.getElementById('logo-path-' + id).value = path;
        var img = document.getElementById('logo-preview-' + id);
        var placeholder = document.getElementById('logo-placeholder-' + id);
        img.src = url;
        img.classList.remove('hidden');
        placeholder.classList.add('hidden');
    });
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/brands/index.blade.php ENDPATH**/ ?>