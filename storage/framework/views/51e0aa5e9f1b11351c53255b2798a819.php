<?php $__env->startSection('title', $landingPage ? 'Edit: '.$landingPage->headline : 'New Landing Page'); ?>
<?php $__env->startSection('page-title', $landingPage ? 'Edit Landing Page' : 'New Landing Page'); ?>
<?php $__env->startSection('breadcrumb', 'Landing Pages / ' . ($landingPage ? 'Edit' : 'New')); ?>

<?php
    $sections = $landingPage->sections ?? \App\Models\LandingPage::defaultSections();
    $theme = $landingPage->theme ?? \App\Models\LandingPage::defaultTheme();
    // Fill in any keys missing from an older/partial record so Alpine always has a stable shape
    $sections = array_replace_recursive(\App\Models\LandingPage::defaultSections(), $sections);
    $theme = array_replace(\App\Models\LandingPage::defaultTheme(), $theme);
?>
<?php echo $__env->make('partials.media-picker', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startSection('content'); ?>
<form method="POST"
    action="<?php echo e($landingPage ? route('admin.landing-pages.update', $landingPage) : route('admin.landing-pages.store')); ?>"
    enctype="multipart/form-data" id="lp-form" x-data="landingPageForm()">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($landingPage): ?> <?php echo method_field('PUT'); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php echo csrf_field(); ?>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        
        <div class="xl:col-span-1 space-y-5">

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Basics</h2>

                <div class="space-y-3">
                    <div>
                        <label class="form-label">Product *</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Select product…</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p->id); ?>" <?php if(old('product_id', $landingPage->product_id ?? null) == $p->id): echo 'selected'; endif; ?>>
                                <?php echo e($p->name); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($p->sku): ?> (<?php echo e($p->sku); ?>) <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="form-label">URL Slug *</label>
                        <div class="flex items-center gap-1 text-xs text-gray-400 mb-1"><?php echo e(url('/')); ?>/<span x-text="slug || 'your-slug-here'"></span></div>
                        <input type="text" name="slug" x-model="slug" @input="slugEdited = true"
                            class="form-input <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" <?php if(old('status', $landingPage->status ?? 'draft') === 'draft'): echo 'selected'; endif; ?>>Draft (only visible to admins)</option>
                            <option value="published" <?php if(old('status', $landingPage->status ?? 'draft') === 'published'): echo 'selected'; endif; ?>>Published (live)</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Eyebrow badge <span class="text-gray-400 font-normal">e.g. "Flash Sale · 7 days left"</span></label>
                        <input type="text" name="eyebrow_text" value="<?php echo e(old('eyebrow_text', $landingPage->eyebrow_text ?? '')); ?>" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Headline *</label>
                        <input type="text" name="headline" x-model="headline" @input="if(!slugEdited) slug = slugify(headline)"
                            class="form-input <?php $__errorArgs = ['headline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['headline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="form-error"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="form-label">Subheadline</label>
                        <input type="text" name="subheadline" value="<?php echo e(old('subheadline', $landingPage->subheadline ?? '')); ?>" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Sale badge <span class="text-gray-400 font-normal">e.g. "🔥 Clearance Sale"</span></label>
                        <input type="text" name="badge_text" value="<?php echo e(old('badge_text', $landingPage->badge_text ?? '')); ?>" class="form-input">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Hero Image</h2>
                <div id="hero-preview" class="w-full h-40 bg-gray-50 rounded-lg border flex items-center justify-center overflow-hidden cursor-pointer mb-2"
                    onclick="openMediaPicker('lp-hero', function(path, url){
                        document.getElementById('hero-media-path').value = path;
                        document.getElementById('hero-preview').innerHTML = '<img src=\'' + url + '\' style=\'width:100%;height:100%;object-fit:contain\'>';
                    })">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($landingPage?->hero_image): ?>
                    <img src="<?php echo e(asset('storage/' . $landingPage->hero_image)); ?>" style="width:100%;height:100%;object-fit:contain">
                    <?php else: ?>
                    <div class="text-center text-gray-400">
                        <i class="fas fa-image text-3xl block mb-1.5"></i>
                        <p class="text-xs">Pick from media library</p>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <input type="hidden" name="hero_image_media_path" id="hero-media-path" value="<?php echo e($landingPage->hero_image ?? ''); ?>">
                <p class="text-xs text-gray-400">Or upload a new file:</p>
                <input type="file" name="hero_image_upload" accept="image/*" class="form-input mt-1 text-xs">
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Pricing & Urgency</h2>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Price override <span class="text-gray-400 font-normal">blank = product's own price</span></label>
                        <input type="number" step="0.01" min="0" name="price" value="<?php echo e(old('price', $landingPage->price ?? '')); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Compare-at (strikethrough) price</label>
                        <input type="number" step="0.01" min="0" name="compare_at_price" value="<?php echo e(old('compare_at_price', $landingPage->compare_at_price ?? '')); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Countdown ends at <span class="text-gray-400 font-normal">optional</span></label>
                        <input type="datetime-local" name="countdown_end_at"
                            value="<?php echo e(old('countdown_end_at', $landingPage?->countdown_end_at?->format('Y-m-d\TH:i'))); ?>" class="form-input">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Theme Colors</h2>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Accent (headline / highlights)</label>
                        <input type="color" x-model="theme.accent" class="w-full h-10 rounded-lg border cursor-pointer">
                    </div>
                    <div>
                        <label class="form-label">CTA (buttons / price)</label>
                        <input type="color" x-model="theme.cta" class="w-full h-10 rounded-lg border cursor-pointer">
                    </div>
                </div>
                <input type="hidden" name="theme" :value="JSON.stringify(theme)">
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">SEO / Meta</h2>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Meta title <span class="text-gray-400 font-normal">blank = headline</span></label>
                        <input type="text" name="meta_title" value="<?php echo e(old('meta_title', $landingPage->meta_title ?? '')); ?>" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Meta description</label>
                        <textarea name="meta_description" rows="2" class="form-input resize-none"><?php echo e(old('meta_description', $landingPage->meta_description ?? '')); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Delivery & Return Notes</h2>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Shipping note <span class="text-gray-400 font-normal">blank = default site delivery text</span></label>
                        <textarea name="shipping_note" rows="2" class="form-input resize-none"><?php echo e(old('shipping_note', $landingPage->shipping_note ?? '')); ?></textarea>
                    </div>
                    <div>
                        <label class="form-label">Return policy note</label>
                        <textarea name="return_policy_note" rows="2" class="form-input resize-none"><?php echo e(old('return_policy_note', $landingPage->return_policy_note ?? '')); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="xl:col-span-2 space-y-5">

            <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 flex items-center justify-between gap-3 flex-wrap">
                <p class="text-sm text-teal-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Toggle sections on/off per page — the price card, quick-order form, and sticky bar are always shown.
                </p>
                <div class="flex gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($landingPage): ?>
                    <a href="<?php echo e(url($landingPage->slug)); ?>" target="_blank" class="btn-outline btn-sm text-xs">
                        <i class="fas fa-eye mr-1"></i>Preview
                    </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button type="submit" class="btn-primary btn-sm">
                        <i class="fas fa-save mr-1.5"></i><?php echo e($landingPage ? 'Save Changes' : 'Create Landing Page'); ?>

                    </button>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">Problem Pills <span class="text-xs font-normal text-gray-400">short pain-point tags near the headline</span></h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.problems.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div class="space-y-2" x-show="sections.problems.enabled">
                    <template x-for="(item, i) in sections.problems.items" :key="i">
                        <div class="flex gap-2">
                            <input type="text" x-model="item.icon" placeholder="🛢️" class="form-input w-16 text-center">
                            <input type="text" x-model="item.text" placeholder="তেলতেলে ত্বক" class="form-input flex-1">
                            <button type="button" @click="sections.problems.items.splice(i,1)" class="btn-danger btn-sm px-3">×</button>
                        </div>
                    </template>
                    <button type="button" @click="sections.problems.items.push({icon:'',text:''})" class="btn-secondary btn-sm text-xs">+ Add pill</button>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">Formula / Feature Grid <span class="text-xs font-normal text-gray-400">key ingredients or features</span></h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.formula.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div class="space-y-3" x-show="sections.formula.enabled">
                    <template x-for="(item, i) in sections.formula.items" :key="i">
                        <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 rounded-lg">
                            <input type="text" x-model="item.icon" placeholder="✨" class="form-input col-span-1 text-center">
                            <input type="text" x-model="item.title_en" placeholder="Brighten & Repair" class="form-input col-span-3">
                            <input type="text" x-model="item.tag" placeholder="Niacinamide 10%" class="form-input col-span-3">
                            <textarea x-model="item.desc" placeholder="Description…" rows="1" class="form-input col-span-4 resize-none"></textarea>
                            <button type="button" @click="sections.formula.items.splice(i,1)" class="btn-danger btn-sm col-span-1">×</button>
                        </div>
                    </template>
                    <button type="button" @click="sections.formula.items.push({icon:'',title_en:'',tag:'',desc:''})" class="btn-secondary btn-sm text-xs">+ Add item</button>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">Benefits List</h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.benefits.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div class="space-y-3" x-show="sections.benefits.enabled">
                    <template x-for="(item, i) in sections.benefits.items" :key="i">
                        <div class="grid grid-cols-12 gap-2 items-start p-3 bg-gray-50 rounded-lg">
                            <input type="text" x-model="item.icon" placeholder="🛢️" class="form-input col-span-1 text-center">
                            <input type="text" x-model="item.title_en" placeholder="Less oil, less shine" class="form-input col-span-4">
                            <textarea x-model="item.desc" placeholder="Description…" rows="1" class="form-input col-span-6 resize-none"></textarea>
                            <button type="button" @click="sections.benefits.items.splice(i,1)" class="btn-danger btn-sm col-span-1">×</button>
                        </div>
                    </template>
                    <button type="button" @click="sections.benefits.items.push({icon:'',title_en:'',desc:''})" class="btn-secondary btn-sm text-xs">+ Add benefit</button>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">How To Use <span class="text-xs font-normal text-gray-400">numbered automatically</span></h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.how_to_use.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div class="space-y-3" x-show="sections.how_to_use.enabled">
                    <template x-for="(item, i) in sections.how_to_use.items" :key="i">
                        <div class="flex gap-2 items-start p-3 bg-gray-50 rounded-lg">
                            <span class="text-sm font-bold text-gray-400 mt-2" x-text="(i+1)+'.'"></span>
                            <input type="text" x-model="item.title_en" placeholder="Cleanse First" class="form-input flex-1">
                            <textarea x-model="item.desc" placeholder="Description…" rows="1" class="form-input flex-[2] resize-none"></textarea>
                            <button type="button" @click="sections.how_to_use.items.splice(i,1)" class="btn-danger btn-sm">×</button>
                        </div>
                    </template>
                    <button type="button" @click="sections.how_to_use.items.push({title_en:'',desc:''})" class="btn-secondary btn-sm text-xs">+ Add step</button>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">Ingredients / Specs</h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.ingredients.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div class="space-y-3" x-show="sections.ingredients.enabled">
                    <div>
                        <label class="form-label">Full ingredient / spec list</label>
                        <textarea x-model="sections.ingredients.text" rows="3" class="form-input resize-none"></textarea>
                    </div>
                    <div>
                        <label class="form-label">Caution / usage warning</label>
                        <textarea x-model="sections.ingredients.caution" rows="2" class="form-input resize-none"></textarea>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-800">Customer Reviews</h2>
                        <p class="text-xs text-gray-400 mt-1">
                            Pulls real approved reviews for this product — never fabricated testimonials.
                            Add reviews via <a href="<?php echo e(route('admin.reviews')); ?>" class="text-teal-600 underline" target="_blank">Reviews → Bulk Import</a> if this product doesn't have any yet.
                        </p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer text-sm flex-shrink-0">
                        <input type="checkbox" x-model="sections.reviews.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">FAQ</h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.faq.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div class="space-y-3" x-show="sections.faq.enabled">
                    <template x-for="(item, i) in sections.faq.items" :key="i">
                        <div class="p-3 bg-gray-50 rounded-lg space-y-2">
                            <div class="flex gap-2">
                                <input type="text" x-model="item.q" placeholder="Question…" class="form-input flex-1">
                                <button type="button" @click="sections.faq.items.splice(i,1)" class="btn-danger btn-sm">×</button>
                            </div>
                            <textarea x-model="item.a" placeholder="Answer…" rows="2" class="form-input resize-none"></textarea>
                        </div>
                    </template>
                    <button type="button" @click="sections.faq.items.push({q:'',a:''})" class="btn-secondary btn-sm text-xs">+ Add question</button>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">Trust Badges <span class="text-xs font-normal text-gray-400">short strip, e.g. "✓ 100% Authentic"</span></h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.trust_badges.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div class="space-y-2" x-show="sections.trust_badges.enabled">
                    <template x-for="(item, i) in sections.trust_badges.items" :key="i">
                        <div class="flex gap-2">
                            <input type="text" x-model="sections.trust_badges.items[i]" placeholder="✓ 100% Authentic" class="form-input flex-1">
                            <button type="button" @click="sections.trust_badges.items.splice(i,1)" class="btn-danger btn-sm">×</button>
                        </div>
                    </template>
                    <button type="button" @click="sections.trust_badges.items.push('')" class="btn-secondary btn-sm text-xs">+ Add badge</button>
                </div>
            </div>

            
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between mb-4 pb-2 border-b">
                    <h2 class="font-bold text-gray-800">Gallery</h2>
                    <label class="flex items-center gap-2 cursor-pointer text-sm">
                        <input type="checkbox" x-model="sections.gallery.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
                <div x-show="sections.gallery.enabled">
                    <div class="flex flex-wrap gap-2 mb-2">
                        <template x-for="(path, i) in sections.gallery.images" :key="i">
                            <div class="relative w-20 h-20 bg-gray-50 rounded-lg overflow-hidden border">
                                <img :src="'<?php echo e(asset('storage')); ?>/' + path" class="w-full h-full object-cover">
                                <button type="button" @click="sections.gallery.images.splice(i,1)"
                                    class="absolute top-0 right-0 bg-red-500 text-white w-5 h-5 text-xs flex items-center justify-center rounded-bl">×</button>
                            </div>
                        </template>
                    </div>
                    <button type="button" class="btn-secondary btn-sm text-xs"
                        @click="openMediaPicker('lp-gallery', (path) => sections.gallery.images.push(path))">
                        + Add image
                    </button>
                </div>
            </div>

            <input type="hidden" name="sections" :value="JSON.stringify(sections)">

            <button type="submit" class="btn-primary w-full py-3">
                <i class="fas fa-save mr-1.5"></i><?php echo e($landingPage ? 'Save Changes' : 'Create Landing Page'); ?>

            </button>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function landingPageForm() {
    return {
        headline: <?php echo json_encode(old('headline', $landingPage->headline ?? ''), 512) ?>,
        slug: <?php echo json_encode(old('slug', $landingPage->slug ?? ''), 512) ?>,
        slugEdited: <?php echo e($landingPage ? 'true' : 'false'); ?>,
        theme: <?php echo json_encode($theme, 15, 512) ?>,
        sections: <?php echo json_encode($sections, 15, 512) ?>,

        slugify(text) {
            return text.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
        },
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/admin/landing-pages/form.blade.php ENDPATH**/ ?>