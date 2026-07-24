@extends('layouts.admin')
@section('title', $landingPage ? 'Edit: '.$landingPage->headline : 'New Landing Page')
@section('page-title', $landingPage ? 'Edit Landing Page' : 'New Landing Page')
@section('breadcrumb', 'Landing Pages / ' . ($landingPage ? 'Edit' : 'New'))

@php
    $sections = $landingPage->sections ?? \App\Models\LandingPage::defaultSections();
    $theme = $landingPage->theme ?? \App\Models\LandingPage::defaultTheme();
    // Fill in any keys missing from an older/partial record so Alpine always has a stable shape
    $sections = array_replace_recursive(\App\Models\LandingPage::defaultSections(), $sections);
    $theme = array_replace(\App\Models\LandingPage::defaultTheme(), $theme);
@endphp
@include('partials.media-picker')

@section('content')
<form method="POST"
    action="{{ $landingPage ? route('admin.landing-pages.update', $landingPage) : route('admin.landing-pages.store') }}"
    enctype="multipart/form-data" id="lp-form" x-data="landingPageForm()">
    @if($landingPage) @method('PUT') @endif
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- ── LEFT: core fields ─────────────────────────────────── --}}
        <div class="xl:col-span-1 space-y-5">

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Basics</h2>

                <div class="space-y-3">
                    <div>
                        <label class="form-label">Product *</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">Select product…</option>
                            @foreach($products as $p)
                            <option value="{{ $p->id }}" @selected(old('product_id', $landingPage->product_id ?? null) == $p->id)>
                                {{ $p->name }} @if($p->sku) ({{ $p->sku }}) @endif
                            </option>
                            @endforeach
                        </select>
                        @error('product_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">URL Slug *</label>
                        <div class="flex items-center gap-1 text-xs text-gray-400 mb-1">{{ url('/') }}/<span x-text="slug || 'your-slug-here'"></span></div>
                        <input type="text" name="slug" x-model="slug" @input="slugEdited = true"
                            class="form-input @error('slug') border-red-400 @enderror" required>
                        @error('slug') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" @selected(old('status', $landingPage->status ?? 'draft') === 'draft')>Draft (only visible to admins)</option>
                            <option value="published" @selected(old('status', $landingPage->status ?? 'draft') === 'published')>Published (live)</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Eyebrow badge <span class="text-gray-400 font-normal">e.g. "Flash Sale · 7 days left"</span></label>
                        <input type="text" name="eyebrow_text" value="{{ old('eyebrow_text', $landingPage->eyebrow_text ?? '') }}" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Headline *</label>
                        <input type="text" name="headline" x-model="headline" @input="if(!slugEdited) slug = slugify(headline)"
                            class="form-input @error('headline') border-red-400 @enderror" required>
                        @error('headline') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="form-label">Subheadline</label>
                        <input type="text" name="subheadline" value="{{ old('subheadline', $landingPage->subheadline ?? '') }}" class="form-input">
                    </div>

                    <div>
                        <label class="form-label">Sale badge <span class="text-gray-400 font-normal">e.g. "🔥 Clearance Sale"</span></label>
                        <input type="text" name="badge_text" value="{{ old('badge_text', $landingPage->badge_text ?? '') }}" class="form-input">
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
                    @if($landingPage?->hero_image)
                    <img src="{{ asset('storage/' . $landingPage->hero_image) }}" style="width:100%;height:100%;object-fit:contain">
                    @else
                    <div class="text-center text-gray-400">
                        <i class="fas fa-image text-3xl block mb-1.5"></i>
                        <p class="text-xs">Pick from media library</p>
                    </div>
                    @endif
                </div>
                <input type="hidden" name="hero_image_media_path" id="hero-media-path" value="{{ $landingPage->hero_image ?? '' }}">
                <p class="text-xs text-gray-400">Or upload a new file:</p>
                <input type="file" name="hero_image_upload" accept="image/*" class="form-input mt-1 text-xs">
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Pricing & Urgency</h2>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Price override <span class="text-gray-400 font-normal">blank = product's own price</span></label>
                        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $landingPage->price ?? '') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Compare-at (strikethrough) price</label>
                        <input type="number" step="0.01" min="0" name="compare_at_price" value="{{ old('compare_at_price', $landingPage->compare_at_price ?? '') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Countdown ends at <span class="text-gray-400 font-normal">optional</span></label>
                        <input type="datetime-local" name="countdown_end_at"
                            value="{{ old('countdown_end_at', $landingPage?->countdown_end_at?->format('Y-m-d\TH:i')) }}" class="form-input">
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
                        <input type="text" name="meta_title" value="{{ old('meta_title', $landingPage->meta_title ?? '') }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Meta description</label>
                        <textarea name="meta_description" rows="2" class="form-input resize-none">{{ old('meta_description', $landingPage->meta_description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border p-5">
                <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Delivery & Return Notes</h2>
                <div class="space-y-3">
                    <div>
                        <label class="form-label">Shipping note <span class="text-gray-400 font-normal">blank = default site delivery text</span></label>
                        <textarea name="shipping_note" rows="2" class="form-input resize-none">{{ old('shipping_note', $landingPage->shipping_note ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="form-label">Return policy note</label>
                        <textarea name="return_policy_note" rows="2" class="form-input resize-none">{{ old('return_policy_note', $landingPage->return_policy_note ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── RIGHT: content sections ───────────────────────────── --}}
        <div class="xl:col-span-2 space-y-5">

            <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 flex items-center justify-between gap-3 flex-wrap">
                <p class="text-sm text-teal-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    Toggle sections on/off per page — the price card, quick-order form, and sticky bar are always shown.
                </p>
                <div class="flex gap-2">
                    @if($landingPage)
                    <a href="{{ url($landingPage->slug) }}" target="_blank" class="btn-outline btn-sm text-xs">
                        <i class="fas fa-eye mr-1"></i>Preview
                    </a>
                    @endif
                    <button type="submit" class="btn-primary btn-sm">
                        <i class="fas fa-save mr-1.5"></i>{{ $landingPage ? 'Save Changes' : 'Create Landing Page' }}
                    </button>
                </div>
            </div>

            {{-- Problems --}}
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

            {{-- Formula / feature grid --}}
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

            {{-- Benefits --}}
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

            {{-- How to use --}}
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

            {{-- Ingredients --}}
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

            {{-- Reviews --}}
            <div class="bg-white rounded-xl border p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-gray-800">Customer Reviews</h2>
                        <p class="text-xs text-gray-400 mt-1">
                            Pulls real approved reviews for this product — never fabricated testimonials.
                            Add reviews via <a href="{{ route('admin.reviews') }}" class="text-teal-600 underline" target="_blank">Reviews → Bulk Import</a> if this product doesn't have any yet.
                        </p>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer text-sm flex-shrink-0">
                        <input type="checkbox" x-model="sections.reviews.enabled" class="accent-teal-600"> Enabled
                    </label>
                </div>
            </div>

            {{-- FAQ --}}
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

            {{-- Trust badges --}}
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

            {{-- Gallery --}}
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
                                <img :src="'{{ asset('storage') }}/' + path" class="w-full h-full object-cover">
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
                <i class="fas fa-save mr-1.5"></i>{{ $landingPage ? 'Save Changes' : 'Create Landing Page' }}
            </button>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function landingPageForm() {
    return {
        headline: @json(old('headline', $landingPage->headline ?? '')),
        slug: @json(old('slug', $landingPage->slug ?? '')),
        slugEdited: {{ $landingPage ? 'true' : 'false' }},
        theme: @json($theme),
        sections: @json($sections),

        slugify(text) {
            return text.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
        },
    };
}
</script>
@endpush
