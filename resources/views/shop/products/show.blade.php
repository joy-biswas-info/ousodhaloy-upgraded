@extends('layouts.shop')
@php
    $pixelViewContent = \App\Models\Setting::get('meta_pixel_view_content','true') === 'true';
@endphp
@section('title', $product->name . ' – ' . ($product->generic_name ?? '') . ' – ' . \App\Models\Setting::get('site_name'))
@section('meta_description', $product->meta_description ?? "Buy {$product->name} online. {$product->generic_name} {$product->strength}. Fast delivery in Bangladesh.")

@push('styles')
<style>
/* ── Product page ──────────────────────────────────────────────────────── */
.pdp-img-main {
    aspect-ratio: 1;
    max-height: 420px;
    background: #f8fafb;
    border-radius: 16px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e5e7eb;
    cursor: zoom-in;
    position: relative;
}
.pdp-thumb-rail { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
.pdp-thumb-rail::-webkit-scrollbar { display: none; }
.pdp-thumb {
    flex-shrink: 0; width: 64px; height: 64px;
    border-radius: 10px; border: 2px solid transparent;
    overflow: hidden; background: #f8fafb; cursor: pointer;
    transition: border-color .15s, transform .15s;
}
.pdp-thumb.active { border-color: var(--teal); transform: scale(1.05); }
.pdp-thumb:hover:not(.active) { border-color: #d1fae5; }

/* Sticky ATC bar */
.pdp-sticky-bar {
    position: fixed; bottom: 0; left: 0; right: 0;
    background: #fff; border-top: 1px solid #e5e7eb;
    padding: 10px 16px; z-index: 80;
    display: flex; align-items: center; gap: 10px;
    box-shadow: 0 -4px 20px rgba(0,0,0,.08);
    transform: translateY(100%);
    transition: transform .25s ease;
}
.pdp-sticky-bar.visible { transform: translateY(0); }
@media (min-width: 1024px) { .pdp-sticky-bar { left: 256px; } }

/* Qty stepper */
.qty-btn {
    width: 36px; height: 36px; border-radius: 10px;
    border: 1.5px solid #e5e7eb; background: #fff;
    font-size: 18px; font-weight: 700; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: all .15s; color: #374151;
}
.qty-btn:hover { border-color: var(--teal); color: var(--teal); background: var(--teal-bg); }

/* Badge pill */
.pdp-badge {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 600; padding: 4px 10px;
    border-radius: 20px;
}

/* Trust strip */
.trust-item { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600; color: #374151; }

/* Tab nav */
.pdp-tab-btn {
    padding: 10px 18px; font-size: 13px; font-weight: 600;
    color: #6b7280; cursor: pointer; border-bottom: 2px solid transparent;
    white-space: nowrap; transition: all .15s; background: none; border-top: none;
    border-left: none; border-right: none; font-family: inherit;
}
.pdp-tab-btn.active { color: var(--teal); border-bottom-color: var(--teal); }

/* Lightbox */
.pdp-lightbox {
    display: none; position: fixed; inset: 0; background: rgba(0,0,0,.9);
    z-index: 200; align-items: center; justify-content: center;
}
.pdp-lightbox.open { display: flex; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-3 sm:px-4 py-4" x-data="productPage()">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-gray-400 mb-4 flex items-center gap-1.5 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-teal-600 transition-colors">Home</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        @if($product->category)
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}"
            class="hover:text-teal-600 transition-colors">{{ $product->category->name }}</a>
        <i class="fas fa-chevron-right text-[9px]"></i>
        @endif
        <span class="text-gray-600 font-medium truncate max-w-[200px]">{{ $product->name }}</span>
    </nav>

    {{-- ── MAIN PRODUCT BLOCK ───────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border overflow-hidden mb-5 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

            {{-- Images --}}
            <div class="p-5 border-b md:border-b-0 md:border-r border-gray-100">
                {{-- Main image --}}
                <div class="pdp-img-main mb-3" @click="lightboxOpen = true">
                    <template x-if="activeImg">
                        <img :src="activeImg" alt="{{ $product->name }}"
                            class="max-h-full max-w-full object-contain transition-opacity duration-200">
                    </template>
                    <template x-if="!activeImg">
                        <span class="text-8xl select-none">💊</span>
                    </template>
                    {{-- Zoom hint --}}
                    <div class="absolute bottom-3 right-3 bg-black/30 text-white text-[10px] px-2 py-1 rounded-lg backdrop-blur-sm">
                        <i class="fas fa-search-plus mr-1"></i>Click to zoom
                    </div>
                    {{-- Discount badge overlay --}}
                    @if($product->mrp && $product->mrp > $product->effective_price)
                    <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-black px-2.5 py-1 rounded-lg shadow">
                        {{ $product->discount_percentage }}% OFF
                    </div>
                    @endif
                </div>

                {{-- Thumbnail rail --}}
                @php $allImages = array_values(array_filter(array_merge([$product->thumbnail_url], $product->image_urls ?? []))); @endphp
                @if(count($allImages) > 1)
                <div class="pdp-thumb-rail">
                    @foreach($allImages as $imgUrl)
                    <button @click="setImg('{{ $imgUrl }}')"
                        :class="activeImg === '{{ $imgUrl }}' ? 'active' : ''"
                        class="pdp-thumb">
                        <img src="{{ $imgUrl }}" alt="" class="w-full h-full object-contain p-1">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="p-5 flex flex-col">

                {{-- Brand + badges --}}
                <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                    @if($product->brand)
                    <a href="{{ route('shop.index', ['brand' => $product->brand->slug]) }}"
                        class="text-xs font-bold uppercase tracking-wider text-teal-600 hover:underline">
                        {{ $product->brand->name }}
                    </a>
                    @endif
                    <div class="flex gap-1.5 flex-wrap">
                        @if($product->requires_prescription)
                        <span class="pdp-badge bg-blue-100 text-blue-700">💊 Rx</span>
                        @endif
                        @if($product->express_delivery)
                        <span class="pdp-badge bg-green-100 text-green-700">🚀 Express</span>
                        @endif
                        @if($product->is_flash_sale)
                        <span class="pdp-badge bg-orange-100 text-orange-700">⚡ Flash Sale</span>
                        @endif
                        @if($product->is_in_stock)
                        <span class="pdp-badge bg-teal-100 text-teal-700">✓ In Stock</span>
                        @else
                        <span class="pdp-badge bg-red-100 text-red-700">✗ Out of Stock</span>
                        @endif
                    </div>
                </div>

                {{-- Name --}}
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 leading-tight mb-1">
                    {{ $product->name }}
                </h1>
                @if($product->generic_name)
                <p class="text-sm text-gray-500 mb-2">{{ $product->generic_name }}
                    @if($product->strength)<span class="text-gray-400">· {{ $product->strength }}</span>@endif
                    @if($product->form)<span class="text-gray-400">· {{ $product->form }}</span>@endif
                </p>
                @endif

                {{-- Rating --}}
                @if($product->rating_count > 0)
                <div class="flex items-center gap-2 mb-3">
                    <div class="flex gap-0.5">
                        @for($i=1; $i<=5; $i++)
                        <i class="fas fa-star text-xs {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500 font-semibold">{{ $product->average_rating }}</span>
                    <span class="text-xs text-gray-400">({{ $product->rating_count }} reviews)</span>
                </div>
                @endif

                {{-- Price block --}}
                <div class="bg-gradient-to-r from-teal-50 to-white rounded-2xl p-4 mb-4 border border-teal-100">
                    <div class="flex items-baseline gap-3 flex-wrap mb-1">
                        <span class="text-3xl font-black" style="color:var(--teal)">
                            ৳{{ number_format($product->effective_price, 2) }}
                        </span>
                        @if($product->mrp && $product->mrp > $product->effective_price)
                        <span class="text-lg text-gray-400 line-through font-medium">
                            ৳{{ number_format($product->mrp, 2) }}
                        </span>
                        <span class="text-sm font-black text-white bg-red-500 px-2.5 py-0.5 rounded-lg">
                            Save ৳{{ number_format($product->mrp - $product->effective_price, 2) }}
                        </span>
                        @endif
                    </div>
                    @if($product->pack_size)
                    <p class="text-xs text-gray-500">
                        <span class="font-semibold">{{ $product->pack_size }}</span> per {{ $product->unit }}
                    </p>
                    @endif
                    @if($product->is_flash_sale && $product->flash_sale_ends_at?->isFuture())
                    <p class="text-xs text-orange-600 font-bold mt-1 flex items-center gap-1">
                        <i class="fas fa-fire-alt"></i>
                        Flash sale ends {{ $product->flash_sale_ends_at->diffForHumans() }}
                    </p>
                    @endif
                </div>

                @if($product->is_in_stock)
                {{-- Qty + ATC --}}
                @if(!$product->requires_prescription)
                <div class="flex items-center gap-3 mb-3">
                    <div class="flex items-center gap-1">
                        <button class="qty-btn" @click="qty = Math.max({{ $product->min_order_qty }}, qty-1)">−</button>
                        <span x-text="qty"
                            class="w-12 text-center font-black text-lg text-gray-800"></span>
                        <button class="qty-btn" @click="qty = Math.min({{ min($product->max_order_qty, $product->stock) }}, qty+1)">+</button>
                    </div>
                    <button @click="addToCartWithQty({{ $product->id }}, qty)"
                        class="flex-1 py-3 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2 transition-all active:scale-95"
                        style="background:var(--teal)"
                        :style="added ? 'background:#16a34a' : 'background:var(--teal)'">
                        <i class="fas" :class="added ? 'fa-check' : 'fa-cart-plus'"></i>
                        <span x-text="added ? 'Added to Cart!' : 'Add to Cart'"></span>
                    </button>
                    @auth
                    <button class="w-11 h-11 rounded-xl border-2 border-gray-200 flex items-center justify-center
                        hover:border-red-300 hover:bg-red-50 transition-all" title="Wishlist">
                        <i class="fas fa-heart text-gray-300 hover:text-red-400"></i>
                    </button>
                    @endauth
                </div>
                @else
                <div class="mb-3 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700">
                    <p class="font-bold mb-1"><i class="fas fa-file-prescription mr-1.5"></i>Prescription Required</p>
                    <p class="text-xs mb-3 text-blue-600">Please upload your prescription at checkout.</p>
                    <button onclick="addToCart({{ $product->id }})"
                        class="w-full py-2.5 rounded-xl text-white font-bold text-sm"
                        style="background:var(--teal)">
                        <i class="fas fa-cart-plus mr-1.5"></i>Add to Cart (Upload Rx at checkout)
                    </button>
                </div>
                @endif

                {{-- Buy Now --}}
                <a href="{{ route('checkout.index') }}"
                    onclick="addToCart({{ $product->id }}); return true;"
                    class="w-full py-3 rounded-xl border-2 text-center font-bold text-sm mb-4 block transition-all hover:shadow-md"
                    style="border-color:var(--teal);color:var(--teal)">
                    <i class="fas fa-bolt mr-1.5"></i>Buy Now — Fast Checkout
                </a>
                @else
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-center text-red-600 font-semibold text-sm">
                    <i class="fas fa-times-circle mr-1.5"></i>Out of Stock — check back soon
                </div>
                @endif

               

                {{-- Delivery info --}}
                <div class="mt-3 bg-teal-50 rounded-xl p-3 text-xs text-teal-700 space-y-1">
                     {{-- Product meta --}}
                <div class="pt-3 space-y-1.5 text-xs text-gray-500">
                    @if($product->sku)
                    <div class="flex gap-2"><span class="font-semibold text-gray-600 w-20 flex-shrink-0">SKU</span><span>{{ $product->sku }}</span></div>
                    @endif
                    @if($product->category)
                    <div class="flex gap-2"><span class="font-semibold text-gray-600 w-20 flex-shrink-0">Category</span>
                        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}"
                            class="hover:underline" style="color:var(--teal)">{{ $product->category->name }}</a>
                    </div>
                    @endif
                    @if($product->pack_size)
                    <div class="flex gap-2"><span class="font-semibold text-gray-600 w-20 flex-shrink-0">Pack Size</span><span>{{ $product->pack_size }}</span></div>
                    @endif
                    <div class="flex gap-2"><span class="font-semibold text-gray-600 w-20 flex-shrink-0">Views</span><span>{{ number_format($product->views) }}</span></div>
                </div>
                    <p><i class="fas fa-truck mr-1.5"></i>Free delivery on orders above ৳{{ number_format(\App\Models\Setting::get('free_delivery_min', 2000)) }}</p>
                    <p><i class="fas fa-certificate mr-1.5"></i>100% genuine product</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CONTENT TABS ─────────────────────────────────────────────── --}}
    @if($product->tabs && count($product->tabs) > 0)
    <div class="bg-white rounded-2xl border mb-5 shadow-sm overflow-hidden" x-data="{ activeTab: 0 }">
        <div class="flex border-b overflow-x-auto" style="scrollbar-width:none">
            @foreach($product->tabs as $i => $tab)
            @if(trim(strip_tags($tab['content'] ?? '')))
            <button @click="activeTab = {{ $i }}"
                :class="activeTab === {{ $i }} ? 'active' : ''"
                class="pdp-tab-btn">
                {{ $tab['label'] }}
            </button>
            @endif
            @endforeach
        </div>
        @foreach($product->tabs as $i => $tab)
        @if(trim(strip_tags($tab['content'] ?? '')))
        <div x-show="activeTab === {{ $i }}"
            class="p-5 prose max-w-none text-sm leading-relaxed text-gray-700">
            {!! $tab['content'] !!}
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- ── REVIEWS ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border mb-5 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-black text-gray-800 text-lg">
                Customer Reviews
                @if($product->rating_count > 0)
                <span class="text-sm font-normal text-gray-400 ml-1">({{ $product->rating_count }})</span>
                @endif
            </h2>
            @if($product->rating_count > 0)
            <div class="flex items-center gap-2">
                <span class="text-2xl font-black" style="color:var(--teal)">{{ $product->average_rating }}</span>
                <div class="flex flex-col gap-0.5">
                    <div class="flex gap-0.5">
                        @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star text-xs {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-400">out of 5</span>
                </div>
            </div>
            @endif
        </div>
        @forelse($product->reviews as $review)
        <div class="border-b last:border-0 py-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                    style="background:var(--teal)">
                    {{ strtoupper(substr($review->user->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <div>
                            <span class="text-sm font-bold text-gray-800">{{ $review->user->name }}</span>
                            <div class="flex gap-0.5 mt-0.5">
                                @for($i=1;$i<=5;$i++)
                                <i class="fas fa-star text-[10px] {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</span>
                    </div>
                    @if($review->title)
                    <p class="text-sm font-semibold text-gray-700 mt-1">{{ $review->title }}</p>
                    @endif
                    @if($review->body)
                    <p class="text-sm text-gray-600 mt-1 leading-relaxed">{{ $review->body }}</p>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="py-8 text-center">
            <div class="text-4xl mb-3">⭐</div>
            <p class="text-sm font-semibold text-gray-700">No reviews yet</p>
            <p class="text-xs text-gray-400 mt-1">Be the first to review this product</p>
        </div>
        @endforelse
    </div>

    {{-- ── RELATED PRODUCTS ─────────────────────────────────────────── --}}
    @if($related->count() > 0)
    <div class="mb-5">
        <h2 class="font-black text-gray-800 text-lg mb-4 flex items-center gap-2">
            <i class="fas fa-pills text-sm" style="color:var(--teal)"></i>
            Similar Products
        </h2>
        <div class="flex gap-3 overflow-x-auto pb-3" style="scrollbar-width:none">
            @foreach($related as $p)
            @include('shop.partials.product-card', ['product' => $p])
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- ── STICKY ADD-TO-CART BAR ─────────────────────────────────────── --}}
@if($product->is_in_stock)
<div class="pdp-sticky-bar" id="pdp-sticky" x-data>
    <div class="flex items-center gap-3 flex-1 min-w-0">
        @if($product->thumbnail_url)
        <img src="{{ $product->thumbnail_url }}" class="w-10 h-10 rounded-lg object-contain bg-gray-50 border flex-shrink-0">
        @endif
        <div class="min-w-0">
            <p class="text-sm font-bold text-gray-800 truncate">{{ Str::limit($product->name, 30) }}</p>
            <p class="text-sm font-black" style="color:var(--teal)">৳{{ number_format($product->effective_price, 2) }}</p>
        </div>
    </div>
    <button onclick="addToCart({{ $product->id }})"
        class="flex-shrink-0 py-2.5 px-5 rounded-xl text-white text-sm font-bold flex items-center gap-2"
        style="background:var(--teal)">
        <i class="fas fa-cart-plus"></i>
        <span class="hidden sm:inline">Add to Cart</span>
    </button>
</div>
@endif

{{-- ── LIGHTBOX ──────────────────────────────────────────────────── --}}
<div class="pdp-lightbox" :class="lightboxOpen ? 'open' : ''" @click="lightboxOpen = false">
    <img :src="activeImg" class="max-w-[90vw] max-h-[90vh] object-contain rounded-xl">
    <button @click="lightboxOpen = false"
        class="absolute top-4 right-4 text-white text-3xl leading-none">&times;</button>
</div>

@endsection

@push('scripts')
<script>
function productPage() {
    return {
        qty:         {{ $product->min_order_qty }},
        activeImg:   '{{ $product->thumbnail_url }}',
        lightboxOpen: false,
        added:        false,

        setImg(url) { this.activeImg = url; },

        addToCartWithQty(id, qty) {
            var self = this;
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                    'X-CSRF-TOKEN':  document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({ product_id: id, qty: qty })
            })
            .then(function(r) {
                if (!r.ok && r.status !== 422) throw new Error('Server error');
                return r.json();
            })
            .then(function(data) {
                if (data.success) {
                    document.querySelectorAll('#cart-count, #cart-count-mobile').forEach(function(el) {
                        el.textContent = data.count;
                        el.style.display = 'flex';
                    });
                    self.added = true;
                    setTimeout(function() { self.added = false; }, 2500);
                    showToast(data.message || 'Added to cart!');
                } else {
                    showToast(data.message || 'Could not add to cart', 'error');
                }
            })
            .catch(function() { showToast('Network error — please try again', 'error'); });
        }
    };
}

// Sticky bar: show after scrolling past the main ATC button
(function() {
    var bar  = document.getElementById('pdp-sticky');
    if (!bar) return;
    var trig = 400;
    window.addEventListener('scroll', function() {
        if (window.scrollY > trig) bar.classList.add('visible');
        else bar.classList.remove('visible');
    }, { passive: true });
})();

// Meta Pixel
@if($pixelViewContent ?? false)
document.addEventListener('DOMContentLoaded', function() {
    if (window.fbTrack) {
        window.fbTrack('ViewContent', {
            content_ids:  ['{{ $product->id }}'],
            content_name: '{{ addslashes($product->name) }}',
            content_type: 'product',
            value:        {{ $product->effective_price }},
            currency:     'BDT'
        });
    }
});
@endif
</script>
@endpush