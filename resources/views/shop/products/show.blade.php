@extends('layouts.shop')
@php
    $pixelViewContent = \App\Models\Setting::get('meta_pixel_view_content','true') === 'true';
@endphp
@section('title', $product->name . ' – ' . ($product->generic_name ?? '') . ' – ' . \App\Models\Setting::get('site_name'))
@section('meta_description', $product->meta_description ?? "Buy {$product->name} online. {$product->generic_name} {$product->strength}. Fast delivery in Bangladesh.")

@section('content')
<div class="max-w-7xl mx-auto px-4 py-5">

    {{-- Breadcrumb --}}
    <nav class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
        <a href="{{ route('home') }}" class="hover:text-teal-700">Home</a>
        <span>/</span>
        @if($product->category)
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="hover:text-teal-700">{{ $product->category->name }}</a>
        <span>/</span>
        @endif
        <span class="text-gray-700 font-medium truncate">{{ $product->name }}</span>
    </nav>

    {{-- Product Main --}}
    <div class="bg-white rounded-2xl border p-5 mb-5" x-data="productPage()">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- Images --}}
            <div>
                <div class="bg-gray-50 rounded-xl h-72 sm:h-80 flex items-center justify-center overflow-hidden mb-3 border" x-on:click="lightbox = true">
                    <template x-if="activeImg">
                        <img :src="activeImg" alt="{{ $product->name }}" class="max-h-full max-w-full object-contain cursor-zoom-in">
                    </template>
                    <template x-if="!activeImg">
                        <span class="text-8xl">💊</span>
                    </template>
                </div>
                @php $allImages = array_filter(array_merge([$product->thumbnail_url], $product->image_urls)); @endphp
                @if(count($allImages) > 1)
                <div class="flex gap-2 overflow-x-auto scrollbar-hide">
                    @foreach($allImages as $i => $imgUrl)
                    <button @click="activeImg = '{{ $imgUrl }}'"
                        :class="activeImg === '{{ $imgUrl }}' ? 'border-teal-500' : 'border-gray-200'"
                        class="w-14 h-14 flex-shrink-0 border-2 rounded-lg overflow-hidden bg-gray-50 flex items-center justify-center transition-colors">
                        <img src="{{ $imgUrl }}" alt="" class="max-h-full max-w-full object-contain">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Info --}}
            <div>
                @if($product->brand)
                <a href="{{ route('shop.index', ['brand' => $product->brand->slug]) }}" class="text-xs text-teal-600 font-semibold uppercase tracking-wide hover:underline">{{ $product->brand->name }}</a>
                @endif
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 mt-1 leading-tight">{{ $product->name }}</h1>
                @if($product->generic_name)
                <p class="text-sm text-gray-500 mt-1">{{ $product->generic_name }} {{ $product->strength }}</p>
                @endif

                {{-- Rating --}}
                @if($product->rating_count > 0)
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex gap-0.5">
                        @for($i=1; $i<=5; $i++)
                        <i class="fas fa-star text-sm {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500">{{ $product->average_rating }} ({{ $product->rating_count }} reviews)</span>
                </div>
                @endif

                {{-- Price --}}
                <div class="mt-4 p-4 bg-gray-50 rounded-xl">
                    <div class="flex items-baseline gap-3 flex-wrap">
                        <span class="text-3xl font-black text-teal-700">৳{{ number_format($product->effective_price, 2) }}</span>
                        @if($product->mrp && $product->mrp > $product->effective_price)
                            <span class="text-base text-gray-400 line-through">৳{{ number_format($product->mrp, 2) }}</span>
                            <span class="bg-red-100 text-red-600 text-xs font-bold px-2 py-0.5 rounded-full">{{ $product->discount_percentage }}% OFF</span>
                        @endif
                    </div>
                    @if($product->is_flash_sale && $product->flash_sale_ends_at?->isFuture())
                    <p class="text-xs text-orange-600 font-semibold mt-1">
                        ⚡ Flash sale ends {{ $product->flash_sale_ends_at->diffForHumans() }}
                    </p>
                    @endif
                    @if($product->pack_size)
                    <p class="text-xs text-gray-500 mt-1">Per {{ $product->unit }}: {{ $product->pack_size }}</p>
                    @endif
                </div>

                {{-- Tags / badges --}}
                <div class="flex flex-wrap gap-1.5 mt-3">
                    @if($product->requires_prescription)
                    <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">💊 Prescription Required</span>
                    @endif
                    @if($product->express_delivery)
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">🚀 Express Delivery</span>
                    @endif
                    @if($product->is_flash_sale)
                    <span class="bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">⚡ Flash Sale</span>
                    @endif
                    @if($product->is_in_stock)
                    <span class="bg-teal-100 text-teal-700 text-xs font-semibold px-2.5 py-1 rounded-full">✅ In Stock</span>
                    @else
                    <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">❌ Out of Stock</span>
                    @endif
                </div>

                {{-- Add to cart --}}
                @if($product->is_in_stock)
                @if($product->requires_prescription)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-xl text-sm text-blue-700">
                    <p class="font-bold mb-1"><i class="fas fa-file-prescription mr-1"></i>Prescription Required</p>
                    <p class="text-xs mb-3">This medicine requires a valid prescription from a registered physician. Please upload your prescription at checkout.</p>
                    <button onclick="addToCart({{ $product->id }})" class="btn-primary btn-sm">
                        <i class="fas fa-cart-plus mr-1"></i>Add to Cart (Upload Rx at checkout)
                    </button>
                </div>
                @else
                <div class="mt-4 flex items-center gap-3">
                    <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden">
                        <button @click="qty = Math.max({{ $product->min_order_qty }}, qty-1)" class="px-3 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg leading-none">−</button>
                        <span x-text="qty" class="px-4 py-2.5 font-bold text-sm min-w-[3rem] text-center"></span>
                        <button @click="qty = Math.min({{ min($product->max_order_qty, $product->stock) }}, qty+1)" class="px-3 py-2.5 text-gray-600 hover:bg-gray-50 transition-colors font-bold text-lg leading-none">+</button>
                    </div>
                    <button @click="addToCartWithQty({{ $product->id }}, qty)" class="btn-primary flex-1 py-3">
                        <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                    </button>
                    @auth
                    <button class="btn-outline px-3 py-3" title="Add to wishlist">
                        <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors"></i>
                    </button>
                    @endauth
                </div>
                @endif

                <a href="{{ route('checkout.index') }}" onclick="addToCart({{ $product->id }}); return true;"
                    class="btn-secondary w-full text-center mt-2 py-3 block">
                    <i class="fas fa-bolt mr-1"></i>Buy Now
                </a>
                @else
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 text-center">
                    <i class="fas fa-times-circle mr-1"></i>Out of Stock — check back soon
                </div>
                @endif

                {{-- Product meta --}}
                <div class="mt-4 space-y-1.5 text-xs text-gray-500 border-t pt-3">
                    @if($product->sku) <p><span class="font-semibold text-gray-700">SKU:</span> {{ $product->sku }}</p> @endif
                    @if($product->form) <p><span class="font-semibold text-gray-700">Form:</span> {{ $product->form }}</p> @endif
                    @if($product->category) <p><span class="font-semibold text-gray-700">Category:</span> <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}" class="text-teal-600 hover:underline">{{ $product->category->name }}</a></p> @endif
                    <p><span class="font-semibold text-gray-700">Views:</span> {{ number_format($product->views) }}</p>
                </div>

                {{-- Delivery info --}}
                <div class="mt-3 bg-teal-50 rounded-xl p-3 text-xs text-teal-700 space-y-1">
                    <p><i class="fas fa-truck mr-1.5"></i>Free delivery on orders above ৳{{ number_format(\App\Models\Setting::get('free_delivery_min', 500)) }}</p>
                    <p><i class="fas fa-certificate mr-1.5"></i>100% genuine product, DGDA licensed</p>
                    <p><i class="fas fa-undo mr-1.5"></i>Easy 7-day return policy</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Content tabs --}}
    @if($product->tabs && count($product->tabs) > 0)
    <div class="bg-white rounded-2xl border mb-5" x-data="{ activeTab: 0 }">
        <div class="flex border-b overflow-x-auto scrollbar-hide">
            @foreach($product->tabs as $i => $tab)
            @if(trim(strip_tags($tab['content'] ?? '')))
            <button @click="activeTab = {{ $i }}"
                :class="activeTab === {{ $i }} ? 'border-b-2 border-teal-600 text-teal-700 font-semibold' : 'text-gray-500 hover:text-gray-700'"
                class="px-5 py-3.5 text-sm font-medium whitespace-nowrap transition-colors flex-shrink-0">
                {{ $tab['label'] }}
            </button>
            @endif
            @endforeach
        </div>
        @foreach($product->tabs as $i => $tab)
        @if(trim(strip_tags($tab['content'] ?? '')))
        <div x-show="activeTab === {{ $i }}" class="p-5 prose max-w-none">
            {!! $tab['content'] !!}
        </div>
        @endif
        @endforeach
    </div>
    @endif

    {{-- Reviews --}}
    <div class="bg-white rounded-2xl border mb-5 p-5">
        <h2 class="font-bold text-gray-800 text-lg mb-4">Customer Reviews
            @if($product->rating_count > 0)
            <span class="text-sm font-normal text-gray-500">({{ $product->rating_count }})</span>
            @endif
        </h2>
        @forelse($product->reviews as $review)
        <div class="border-b last:border-0 py-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="flex gap-0.5">
                            @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                        @if($review->title) <span class="text-sm font-semibold text-gray-800">{{ $review->title }}</span> @endif
                    </div>
                    @if($review->body) <p class="text-sm text-gray-600">{{ $review->body }}</p> @endif
                </div>
                <div class="text-right text-xs text-gray-400 flex-shrink-0">
                    <p>{{ $review->user->name }}</p>
                    <p>{{ $review->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-500 py-4 text-center">No reviews yet. Be the first to review!</p>
        @endforelse
    </div>

    {{-- Related products --}}
    @if($related->count() > 0)
    <div class="mb-5">
        <h2 class="font-bold text-gray-800 text-lg mb-4">Similar Products</h2>
        <div class="flex gap-3 overflow-x-auto scrollbar-hide pb-2">
            @foreach($related as $p)
            @include('shop.partials.product-card', ['product' => $p])
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
// Meta Pixel — ViewContent
@if($pixelViewContent ?? false)
document.addEventListener('DOMContentLoaded', () => {
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

function productPage() {
    return {
        qty: {{ $product->min_order_qty }},
        activeImg: '{{ $product->thumbnail_url }}',
        addToCartWithQty(id, qty) {
            fetch('/cart/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ product_id: id, qty })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const count = document.getElementById('cart-count');
                    if (count) { count.textContent = data.count; count.classList.remove('hidden'); }
                    showToast(data.message);
                } else showToast(data.message, 'error');
            });
        }
    };
}
</script>
@endpush