<?php $__env->startSection('page-title', 'Customization'); ?>
<?php $__env->startSection('breadcrumb', 'Customization'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-5" x-data="{ tab: 'branding' }">

        
        <div class="bg-white rounded-xl border p-1.5 flex gap-1 overflow-x-auto">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['branding' => '🎨 Branding', 'banners' => '🖼 Banners', 'site' => '🌐 Site Info']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button @click="tab='<?php echo e($key); ?>'" :class="tab === '<?php echo e($key); ?>' ? 'bg-teal-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                    class="flex-shrink-0 px-4 py-2 rounded-lg text-sm font-semibold transition-colors whitespace-nowrap">
                    <?php echo e($label); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div x-show="tab==='branding'" class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Site Logo</h2>
                <form method="POST" action="<?php echo e(route('admin.customization.save')); ?>" enctype="multipart/form-data"
                    class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="group" value="general">

                    
                    <div id="logo-preview"
                        class="w-full h-32 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center overflow-hidden mb-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($settings['site_logo'])): ?>
                            <img src="<?php echo e(asset('storage/' . $settings['site_logo'])); ?>"
                                class="max-h-full max-w-full object-contain p-4">
                        <?php else: ?>
                            <div class="text-center text-gray-400">
                                <i class="fas fa-image text-3xl mb-2"></i>
                                <p class="text-xs">No logo set</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <input type="hidden" name="logo_media_path" id="logo-media-path"
                        value="<?php echo e($settings['site_logo'] ?? ''); ?>">

                    <button type="button" onclick="openMediaPicker('logo', (path, url) => {
                                                                                document.getElementById('logo-media-path').value = path;
                                                                                document.getElementById('logo-preview').innerHTML = '<img src=\''+url+'\' class=\'max-h-full max-w-full object-contain p-4\'>';
                                                                            })" class="btn-secondary w-full">
                        <i class="fas fa-images mr-2"></i>Pick from Media Library
                    </button>
                    <p class="text-xs text-gray-400 text-center">
                        Upload logo in <a href="<?php echo e(route('admin.media.index')); ?>" target="_blank"
                            class="text-teal-600 underline">Media Library</a> first. Recommended: PNG with transparent
                        background, min 200px height.
                    </p>

                    <button type="submit" class="btn-primary w-full">Save Logo</button>
                </form>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Brand Colors</h2>
                <form method="POST" action="<?php echo e(route('admin.customization.save')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="group" value="general">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [['brand_primary', 'Primary', '#0e7673', 'Main buttons, links, header'], ['brand_dark', 'Dark', '#0a5250', 'Hover states, dark accents'], ['brand_light', 'Light', '#13a09c', 'Badges, highlights'], ['brand_bg', 'Background', '#e6f4f4', 'Subtle bg tints'],]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label, $default, $hint]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div>
                            <label class="form-label"><?php echo e($label); ?></label>
                            <div class="flex items-center gap-2">
                                <input type="color" name="<?php echo e($key); ?>" value="<?php echo e($settings[$key] ?? $default); ?>"
                                    class="h-10 w-12 rounded border cursor-pointer p-0.5"
                                    oninput="document.getElementById('hex-<?php echo e($key); ?>').value=this.value;updatePreview()">
                                <input type="text" id="hex-<?php echo e($key); ?>" value="<?php echo e($settings[$key] ?? $default); ?>"
                                    class="form-input flex-1 font-mono"
                                    oninput="document.querySelector('[name=<?php echo e($key); ?>]').value=this.value;updatePreview()">
                            </div>
                            <p class="text-xs text-gray-400 mt-1"><?php echo e($hint); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div class="rounded-xl p-4 border" id="color-preview"
                        style="background:<?php echo e($settings['brand_bg'] ?? '#e6f4f4'); ?>">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-bold text-sm"
                                id="preview-dot" style="background:<?php echo e($settings['brand_primary'] ?? '#0e7673'); ?>">A</div>
                            <div>
                                <p class="text-sm font-bold" id="preview-text"
                                    style="color:<?php echo e($settings['brand_primary'] ?? '#0e7673'); ?>">Brand Color Preview</p>
                                <p class="text-xs text-gray-500">This is how your brand color looks</p>
                            </div>
                            <button type="button" id="preview-btn"
                                class="ml-auto text-white text-sm font-bold px-4 py-2 rounded-lg"
                                style="background:<?php echo e($settings['brand_primary'] ?? '#0e7673'); ?>">Button</button>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">Save Colors</button>
                </form>
            </div>
        </div>

        
        <div x-show="tab==='banners'" class="space-y-5">

            
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Banner Dimensions</h2>
                <form method="POST" action="<?php echo e(route('admin.customization.save')); ?>"
                    class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="group" value="general">
                    <div>
                        <label class="form-label">Hero Banner Height (px)</label>
                        <input type="number" name="hero_banner_height" value="<?php echo e($settings['hero_banner_height'] ?? 400); ?>"
                            class="form-input" min="200" max="800" step="10">
                        <p class="text-xs text-gray-400 mt-1">Height of the main hero slider (200–800px)</p>
                    </div>
                    <div>
                        <label class="form-label">Promo Banner Height (px)</label>
                        <input type="number" name="promo_banner_height"
                            value="<?php echo e($settings['promo_banner_height'] ?? 120); ?>" class="form-input" min="80" max="300"
                            step="10">
                        <p class="text-xs text-gray-400 mt-1">Height of the small promo cards (80–300px)</p>
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" class="btn-primary">Save Dimensions</button>
                    </div>
                </form>
            </div>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 bg-white rounded-xl border overflow-hidden">
                    <div class="px-5 py-4 border-b flex items-center justify-between">
                        <p class="font-bold text-gray-800">Banners (<?php echo e($banners->total()); ?>)</p>
                        <a href="<?php echo e(route('admin.settings.banners')); ?>" class="btn-secondary btn-sm">Manage All</a>
                    </div>
                    <div class="divide-y">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="p-4 flex items-center gap-4">
                                <div class="w-20 h-12 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center"
                                    style="background:<?php echo e($banner->bg_color); ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->image_url): ?>
                                        <img src="<?php echo e($banner->image_url); ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-white text-xs font-bold opacity-75">No Image</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm text-gray-800 truncate"><?php echo e($banner->title); ?></p>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span
                                            class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-semibold uppercase"><?php echo e($banner->position); ?></span>
                                        <span
                                            class="text-[10px] font-semibold <?php echo e($banner->is_active ? 'text-green-600' : 'text-gray-400'); ?>"><?php echo e($banner->is_active ? 'Active' : 'Hidden'); ?></span>
                                    </div>
                                </div>
                                <form method="POST" action="<?php echo e(route('admin.settings.banners.destroy', $banner)); ?>"
                                    onsubmit="return confirm('Delete?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn-danger btn-sm">Delete</button>
                                </form>
                                <form method="POST" action="<?php echo e(route('admin.settings.banners.update', $banner)); ?>"
                                    onsubmit="return confirm('Update?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn-success btn-sm">Update</button>
                                </form>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="p-8 text-center text-gray-400 text-sm">No banners yet. Add one →</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-4 text-sm">Add New Banner</h3>
                    <form method="POST" action="<?php echo e(route('admin.settings.banners.store')); ?>" enctype="multipart/form-data"
                        class="space-y-3">
                        <?php echo csrf_field(); ?>
                        <div>
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" class="form-input" required placeholder="Flash Sale — 50% OFF">
                        </div>
                        <div>
                            <label class="form-label">Subtitle</label>
                            <input type="text" name="subtitle" class="form-input" placeholder="Shop now and save big">
                        </div>
                        <div>
                            <label class="form-label">Image</label>
                            
                            <div id="banner-img-preview"
                                class="w-full h-24 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200 flex items-center justify-center mb-2 overflow-hidden cursor-pointer"
                                onclick="openMediaPicker('banner', (path, url) => {
                                                                                        document.getElementById('banner-media-path').value = path;
                                                                                        document.getElementById('banner-img-preview').innerHTML = '<img src=\''+url+'\' class=\'w-full h-full object-cover\'>';
                                                                                        document.getElementById('banner-file-input').value = '';
                                                                                    })">
                                <div class="text-center text-gray-400 text-xs">
                                    <i class="fas fa-image text-xl mb-1 block"></i>
                                    Click to pick from Media Library
                                </div>
                            </div>
                            <input type="hidden" name="image_media_path" id="banner-media-path">
                            
                            <input type="file" name="image" id="banner-file-input" accept="image/*"
                                class="form-input text-xs py-1.5" onchange="previewBannerFile(this)">
                            <p class="text-xs text-gray-400 mt-1">Or upload directly</p>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="form-label">Link URL</label>
                                <input type="text" name="link_url" class="form-input" placeholder="/shop">
                            </div>
                            <div>
                                <label class="form-label">Button Text</label>
                                <input type="text" name="button_text" class="form-input" placeholder="Shop Now">
                            </div>
                            <div>
                                <label class="form-label">Badge Text</label>
                                <input type="text" name="badge_text" class="form-input" placeholder="New Arrival">
                            </div>
                            <div>
                                <label class="form-label">BG Color</label>
                                <input type="color" name="bg_color" value="#0e7673"
                                    class="h-10 w-full rounded border cursor-pointer p-0.5">
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Position</label>
                            <select name="position" class="form-select">
                                <option value="hero">Hero (main slider)</option>
                                <option value="promo">Promo (small cards)</option>
                                <option value="sidebar">Sidebar</option>
                            </select>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" checked class="accent-teal-600">
                            <span class="text-sm text-gray-700">Active</span>
                        </label>
                        <button type="submit" class="btn-primary w-full">Add Banner</button>
                    </form>
                </div>
            </div>
        </div>

        
        <div x-show="tab==='site'">
            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Site Information</h2>
                <form method="POST" action="<?php echo e(route('admin.customization.save')); ?>"
                    class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="group" value="general">
                    <div>
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" value="<?php echo e($settings['site_name'] ?? ''); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Tagline</label>
                        <input type="text" name="site_tagline" value="<?php echo e($settings['site_tagline'] ?? ''); ?>"
                            class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Phone</label>
                        <input type="text" name="site_phone" value="<?php echo e($settings['site_phone'] ?? ''); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="site_email" value="<?php echo e($settings['site_email'] ?? ''); ?>"
                            class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Address</label>
                        <input type="text" name="site_address" value="<?php echo e($settings['site_address'] ?? ''); ?>"
                            class="form-input">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="form-label">Messenger / WhatsApp URL</label>
                        <input type="url" name="messenger_url" value="<?php echo e($settings['messenger_url'] ?? ''); ?>"
                            class="form-input" placeholder="https://m.me/yourpage or https://wa.me/880...">
                    </div>
                    <div class="sm:col-span-2">
                        <button type="submit" class="btn-primary">Save Site Info</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php echo $__env->make('partials.media-picker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        function openLogoPicker() {
            openMediaPicker({
                title: 'Pick Site Logo',
                multiple: false,
                onPick(media) {
                    document.getElementById('logo-media-path').value = media.path;
                    document.getElementById('logo-preview').innerHTML =
                        `<img src="${media.url}" style="max-height:100%;max-width:100%;object-fit:contain;padding:12px">`;
                }
            });
        }
        function openBannerPicker() {
            openMediaPicker({
                title: 'Pick Banner Image',
                multiple: false,
                onPick(media) {
                    document.getElementById('banner-media-path').value = media.path;
                    document.getElementById('banner-img-preview').innerHTML =
                        `<img src="${media.url}" style="width:100%;height:100%;object-fit:cover">`;
                    document.getElementById('banner-file-input').value = '';
                }
            });
        }
        function previewBannerFile(input) {
            if (input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    document.getElementById('banner-img-preview').innerHTML =
                        `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
                };
                reader.readAsDataURL(input.files[0]);
                document.getElementById('banner-media-path').value = '';
            }
        }
        function updatePreview() {
            const primary = document.querySelector('[name=brand_primary]')?.value || '#0e7673';
            const bg = document.querySelector('[name=brand_bg]')?.value || '#e6f4f4';
            const preview = document.getElementById('color-preview');
            const dot = document.getElementById('preview-dot');
            const text = document.getElementById('preview-text');
            const btn = document.getElementById('preview-btn');
            if (preview) preview.style.background = bg;
            if (dot) dot.style.background = primary;
            if (text) text.style.color = primary;
            if (btn) btn.style.background = primary;
        }
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/customization/index.blade.php ENDPATH**/ ?>