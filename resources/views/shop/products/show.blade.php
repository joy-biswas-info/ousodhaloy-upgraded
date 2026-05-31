@extends('layouts.shop')
@php
    $pixelViewContent = \App\Models\Setting::get('meta_pixel_view_content', 'true') === 'true';
@endphp
@section('title', $product->name . ' – ' . ($product->generic_name ?? '') . ' – ' .
    \App\Models\Setting::get('site_name'))
@section('meta_description',
    $product->meta_description ??
    "Buy {$product->name} online. {$product->generic_name}
    {$product->strength}. Fast delivery in Bangladesh.")


@section('content')
    <div class="max-w-6xl mx-auto px-3 sm:px-4 py-4" x-data="productPage()">

        {{-- Breadcrumb --}}
        <nav class="text-xs text-gray-400 mb-4 flex items-center gap-1.5 flex-wrap">
            <a href="{{ route('home') }}" class="hover:text-teal-600 transition-colors">Home</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            @if ($product->category)
                <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}"
                    class="hover:text-teal-600 transition-colors">{{ $product->category->name }}</a>
                <i class="fas fa-chevron-right text-[9px]"></i>
            @endif
            <span class="text-gray-600 font-medium truncate max-w-[200px]">{{ $product->name }}</span>
        </nav>

        {{-- ── MAIN PRODUCT BLOCK ── --}}
        <div class="bg-white rounded-2xl border overflow-hidden mb-5 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0">

                {{-- Images --}}
                <div class="p-5 border-b md:border-b-0 md:border-r border-gray-100">
                    <div class="pdp-img-main mb-3" @click="lightboxOpen = true">
                        <template x-if="activeImg">
                            <img :src="activeImg" alt="{{ $product->name }}"
                                class="max-h-full max-w-full object-contain transition-opacity duration-200">
                        </template>
                        <template x-if="!activeImg">
                            <span class="text-8xl select-none">💊</span>
                        </template>
                        
                        @if ($product->mrp && $product->mrp > $product->effective_price)
                            <div
                                class="absolute top-3 left-3 bg-red-500 text-white text-xs font-black px-2.5 py-1 rounded-lg shadow">
                                {{ $product->discount_percentage }}% OFF
                            </div>
                        @endif
                    </div>
                    @php $allImages = array_values(array_filter(array_merge([$product->thumbnail_url], $product->image_urls ?? []))); @endphp
                    @if (count($allImages) > 1)
                        <div class="pdp-thumb-rail">
                            @foreach ($allImages as $imgUrl)
                                <button @click="setImg('{{ $imgUrl }}')"
                                    :class="activeImg === '{{ $imgUrl }}' ? 'active' : ''" class="pdp-thumb">
                                    <img src="{{ $imgUrl }}" alt="" class="w-full h-full object-contain p-1">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Product Info --}}
                <div class="p-5 flex flex-col">
                    <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                        @if ($product->brand)
                            <a href="{{ route('shop.index', ['brand' => $product->brand->slug]) }}"
                                class="text-xs font-bold uppercase tracking-wider text-teal-600 hover:underline">
                                {{ $product->brand->name }}
                            </a>
                        @endif
                        <div class="flex gap-1.5 flex-wrap">
                            @if ($product->requires_prescription)
                                <span class="pdp-badge bg-blue-100 text-blue-700">💊 Rx</span>
                            @endif
                            @if ($product->express_delivery)
                                <span class="pdp-badge bg-green-100 text-green-700">🚀 Express</span>
                            @endif
                            @if ($product->is_flash_sale)
                                <span class="pdp-badge bg-orange-100 text-orange-700">⚡ Flash Sale</span>
                            @endif
                            @if ($product->is_in_stock)
                                <span class="pdp-badge bg-teal-100 text-teal-700">✓ In Stock</span>
                            @else
                                <span class="pdp-badge bg-red-100 text-red-700">✗ Out of Stock</span>
                            @endif
                        </div>
                    </div>

                    <h1 class="text-xl sm:text-2xl font-black text-gray-900 leading-tight mb-1">{{ $product->name }}</h1>
                    @if ($product->generic_name)
                        <p class="text-sm text-gray-500 mb-2">{{ $product->generic_name }}
                            @if ($product->strength)
                                <span class="text-gray-400"> · {{ $product->strength }}</span>
                            @endif
                            @if ($product->form)
                                <span class="text-gray-400"> · {{ $product->form }}</span>
                            @endif
                        </p>
                    @endif

                    @if ($product->rating_count > 0)
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex gap-0.5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i
                                        class="fas fa-star text-xs {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                            <span class="text-xs text-gray-500 font-semibold">{{ $product->average_rating }}</span>
                            <span class="text-xs text-gray-400">({{ $product->rating_count }} reviews)</span>
                        </div>
                    @endif

                    {{-- Price --}}
                    <div class="bg-gradient-to-r from-teal-50 to-white rounded-2xl p-4 mb-4 border border-teal-100">
                        <div class="flex items-baseline gap-3 flex-wrap mb-1">
                            <span class="text-3xl font-black" style="color:var(--teal)">
                                ৳{{ number_format($product->effective_price, 2) }}
                            </span>
                            @if ($product->mrp && $product->mrp > $product->effective_price)
                                <span
                                    class="text-lg text-gray-400 line-through font-medium">৳{{ number_format($product->mrp, 2) }}</span>
                                <span class="text-sm font-black text-white bg-red-500 px-2.5 py-0.5 rounded-lg">
                                    Save ৳{{ number_format($product->mrp - $product->effective_price, 2) }}
                                </span>
                            @endif
                        </div>
                        @if ($product->pack_size)
                            <p class="text-xs text-gray-500"><span class="font-semibold">{{ $product->pack_size }}</span>
                                per {{ $product->unit }}</p>
                        @endif
                        @if ($product->is_flash_sale && $product->flash_sale_ends_at?->isFuture())
                            <p class="text-xs text-orange-600 font-bold mt-1">
                                <i class="fas fa-fire-alt mr-1"></i>Flash sale ends
                                {{ $product->flash_sale_ends_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    @if ($product->is_in_stock)
                        @if (!$product->requires_prescription)
                            <div class="flex items-center gap-3 mb-3">
                                <div class="flex items-center gap-1">
                                    <button class="qty-btn"
                                        @click="qty = Math.max({{ $product->min_order_qty }}, qty-1)">−</button>
                                    <span x-text="qty" class="w-12 text-center font-black text-lg text-gray-800"></span>
                                    <button class="qty-btn"
                                        @click="qty = Math.min({{ min($product->max_order_qty, $product->stock) }}, qty+1)">+</button>
                                </div>
                                <button @click="addToCartWithQty({{ $product->id }}, qty)"
                                    class="flex-1 py-3 rounded-xl text-white font-bold text-sm flex items-center justify-center gap-2 transition-all active:scale-95"
                                    :style="added ? 'background:#16a34a' : 'background:var(--teal)'">
                                    <i class="fas" :class="added ? 'fa-check' : 'fa-cart-plus'"></i>
                                    <span x-text="added ? 'Added to Cart!' : 'Add to Cart'"></span>
                                </button>
                                @auth
                                    <button
                                        class="w-11 h-11 rounded-xl border-2 border-gray-200 flex items-center justify-center hover:border-red-300 hover:bg-red-50 transition-all"
                                        title="Wishlist">
                                        <i class="fas fa-heart text-gray-300"></i>
                                    </button>
                                @endauth
                            </div>
                        @else
                            <div class="mb-3 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700">
                                <p class="font-bold mb-1"><i class="fas fa-file-prescription mr-1.5"></i>Prescription
                                    Required</p>
                                <p class="text-xs mb-3 text-blue-600">Please upload your prescription at checkout.</p>
                                <button onclick="addToCart({{ $product->id }})"
                                    class="w-full py-2.5 rounded-xl text-white font-bold text-sm"
                                    style="background:var(--teal)">
                                    <i class="fas fa-cart-plus mr-1.5"></i>Add to Cart (Upload Rx at checkout)
                                </button>
                            </div>
                        @endif

                        <a href="{{ route('checkout.index') }}" onclick="addToCart({{ $product->id }}); return true;"
                            class="w-full py-3 rounded-xl text-center font-bold text-sm mb-4 block transition-all text-white"
                            style="background:var(--orange, #f97316)">
                            <i class="fas fa-bolt mr-1.5"></i>Buy Now — Fast Checkout
                        </a>
                    @else
                        <div
                            class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-center text-red-600 font-semibold text-sm">
                            <i class="fas fa-times-circle mr-1.5"></i>Out of Stock — check back soon
                        </div>
                    @endif

                        {{-- Trust strip --}}
                    <div class="mt-3 bg-teal-50 rounded-xl p-2 text-xs text-teal-700 space-y-1">
                        <div class="grid grid-cols-3 gap-1 my-4">
                            @foreach ([['fas fa-truck', 'Fast Delivery', '24–48 hrs'], ['fas fa-undo', 'Easy Returns', 'Policy'], ['fas fa-lock', 'Secure Pay', 'bKash · Card']] as [$icon, $title, $sub])
                                <div class="flex items-center gap-1 rounded-xl px-1 py-1">
                                    <i class="{{ $icon }} text-sm" style="color:var(--teal)"></i>
                                    <div>
                                        <p class="text-xs font-medium text-gray-700 leading-none">{{ $title }}</p>
                                        <p class="text-[10px] text-gray-400 leading-none mt-0.5">{{ $sub }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ACCORDIONS + RELATED SIDEBAR ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">
            {{-- Accordions — 2/3 width --}}
            <div class="lg:col-span-2 space-y-3 ">
                @if ($product->tabs && count($product->tabs) > 0)
                    @php $firstOpen = true; @endphp
                    <div class="bg-white rounded-2xl shadow-sm overflow-hidden divide-y">
                        @foreach ($product->tabs as $i => $tab)
                            @if (trim(strip_tags($tab['content'] ?? '')))
                                <div class="border-teal-100" x-data="{ open: {{ $firstOpen ? 'true' : 'false' }} }" @php $firstOpen = false; @endphp>
                                    <button type="button" @click="open = !open"
                                        class="w-full flex items-center justify-between px-5 py-4 text-left hover:bg-teal-50 transition-colors group">
                                        <span
                                            class="font-bold text-gray-800 text-sm group-hover:text-teal-700 transition-colors">
                                            {{ $tab['label'] }}
                                        </span>
                                        <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200"
                                            :class="open ? 'rotate-180 text-teal-600' : ''"></i>
                                    </button>
                                    <div x-show="open" x-collapse
                                        class="px-5 pb-5 prose max-w-none text-sm leading-relaxed text-gray-700">
                                        {!! $tab['content'] !!}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                    @include('shop.partials.review-card', [$product])
                @endif
            </div>
            {{-- Related Products — 1/3 width sticky sidebar --}}
                @if ($related->count() > 0)
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm overflow-hidden lg:sticky lg:top-4">
                            <div class="px-4 py-3 flex items-center gap-2 border-b border-teal-100">
                                <i class="fas fa-pills text-sm" style="color:var(--teal)"></i>
                                <h3 class="font-black text-gray-800 text-sm">
                                    @if ($product->generic_name)
                                        More {{ $product->generic_name }}
                                    @else
                                        Similar Products
                                    @endif
                                </h3>
                                <span class="ml-auto text-xs text-gray-400">{{ $related->count() }}</span>
                            </div>
                            <div class="divide-y divide-gray-50 p-2">
                                @foreach ($related as $p)
                                    @include('shop.partials.product-card-list', ['product' => $p])
                                @endforeach
                            </div>
                            <div class="px-4 py-3  bg-gray-50">
                                <a href="{{ route('shop.index', $product->generic_name ? ['q' => $product->generic_name] : ['category' => $product->category?->slug]) }}"
                                    class="text-xs font-bold flex items-center justify-center gap-1.5 transition-colors"
                                    style="color:var(--teal)">
                                    View all
                                    <i class="fas fa-arrow-right text-[10px]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
        </div>
        {{-- Sticky ATC bar --}}
        @if ($product->is_in_stock)
            <div class="pdp-sticky-bar" id="pdp-sticky" x-data>
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    @if ($product->thumbnail_url)
                        <img src="{{ $product->thumbnail_url }}"
                            class="w-10 h-10 rounded-lg object-contain bg-gray-50 border flex-shrink-0">
                    @endif
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate">{{ Str::limit($product->name, 30) }}</p>
                        <p class="text-sm font-black" style="color:var(--teal)">
                            ৳{{ number_format($product->effective_price, 2) }}</p>
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

        {{-- Lightbox --}}
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
                    qty: {{ $product->min_order_qty }},
                    activeImg: '{{ $product->thumbnail_url }}',
                    lightboxOpen: false,
                    added: false,

                    setImg(url) {
                        this.activeImg = url;
                    },

                    addToCartWithQty(id, qty) {
                        var self = this;
                        fetch('/cart/add', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                },
                                body: JSON.stringify({
                                    product_id: id,
                                    qty: qty
                                })
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
                                    setTimeout(function() {
                                        self.added = false;
                                    }, 2500);
                                    showToast(data.message || 'Added to cart!');
                                } else {
                                    showToast(data.message || 'Could not add to cart', 'error');
                                }
                            })
                            .catch(function() {
                                showToast('Network error — please try again', 'error');
                            });
                    }
                };
            }

            (function() {
                var bar = document.getElementById('pdp-sticky');
                if (!bar) return;
                window.addEventListener('scroll', function() {
                    bar.classList.toggle('visible', window.scrollY > 400);
                }, {
                    passive: true
                });
            })();

            @if ($pixelViewContent ?? false)
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.fbTrack) {
                        window.fbTrack('ViewContent', {
                            content_ids: ['{{ $product->id }}'],
                            content_name: '{{ addslashes($product->name) }}',
                            content_type: 'product',
                            value: {{ $product->effective_price }},
                            currency: 'BDT'
                        });
                    }
                });
            @endif
        </script>
    @endpush
