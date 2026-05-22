<div class="product-card">
    <a href="{{ route('shop.product', $product->slug) }}" class="block">
        <div class="card-img">
            @if($product->thumbnail)
                <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" class="max-h-full object-contain" loading="lazy">
            @else
                <span class="text-5xl">💊</span>
            @endif
            @if($product->discount_percentage > 0)
                <span class="badge-discount">-{{ $product->discount_percentage }}%</span>
            @endif
            @if($product->is_flash_sale && $product->flash_sale_ends_at?->isFuture())
                <span class="badge-flash">⚡</span>
            @endif
            @if($product->requires_prescription)
                <span class="badge-rx" style="top: {{ $product->discount_percentage > 0 ? '26px' : '8px' }}">Rx</span>
            @endif
            @if($product->express_delivery)
                <span class="badge-express">🚀 Express</span>
            @endif
            @if(!$product->is_in_stock)
                <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-1 rounded">Out of Stock</span>
                </div>
            @endif
        </div>
    </a>
    <div class="card-body">
        @if($product->brand)
            <p class="card-brand">{{ $product->brand->name }}</p>
        @endif
        <a href="{{ route('shop.product', $product->slug) }}">
            <h3 class="card-name">{{ $product->name }}</h3>
        </a>
        @if($product->generic_name)
            <p class="card-generic">{{ $product->generic_name }}{{ $product->strength ? ' · '.$product->strength : '' }}</p>
        @endif
        <div class="card-price">
            <span class="price-now">৳{{ number_format($product->effective_price, 2) }}</span>
            @if($product->mrp && $product->mrp > $product->effective_price)
                <span class="price-was">৳{{ number_format($product->mrp, 2) }}</span>
            @endif
        </div>
        @if($product->average_rating > 0)
        <div class="flex items-center gap-1 mb-1">
            <div class="flex">
                @for($i = 1; $i <= 5; $i++)
                <i class="fas fa-star text-[10px] {{ $i <= $product->average_rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                @endfor
            </div>
            <span class="text-[10px] text-gray-400">({{ $product->rating_count }})</span>
        </div>
        @endif
        @if($product->is_low_stock)
            <p class="text-[10px] text-orange-600 font-semibold mb-1">⚠ Only {{ $product->stock }} left</p>
        @endif
        @if($product->pack_size)
            <p class="text-[10px] text-gray-400 mb-1">{{ $product->pack_size }}</p>
        @endif
        <button
            @if($product->is_in_stock && !$product->requires_prescription)
                onclick="addToCart({{ $product->id }})"
            @elseif($product->requires_prescription)
                onclick="window.location='{{ route('shop.product', $product->slug) }}'"
            @else
                disabled
            @endif
            class="card-add-btn">
            @if(!$product->is_in_stock)
                Out of Stock
            @elseif($product->requires_prescription)
                <i class="fas fa-file-prescription text-[10px] mr-1"></i>View (Rx)
            @else
                <i class="fas fa-cart-plus text-[10px] mr-1"></i>Add to Cart
            @endif
        </button>
    </div>
</div>
