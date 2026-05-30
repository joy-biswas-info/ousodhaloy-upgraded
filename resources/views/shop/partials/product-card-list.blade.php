{{-- List-style product card for related products sidebar --}}
<a href="{{ route('shop.product', $product->slug) }}"
    class="flex items-center gap-3 p-3 rounded-xl hover:bg-teal-50 transition-all group border border-transparent hover:border-gray-200">

    {{-- Image --}}
    <div class="w-12 h-12 flex-shrink-0 rounded-xl overflow-hidden bg-gray-50 border flex items-center justify-center">
        @if ($product->thumbnail_url)
            <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}"
                class="w-full h-full object-contain p-1 group-hover:scale-105 transition-transform duration-200">
        @else
            <span class="text-2xl">💊</span>
        @endif
    </div>

    {{-- Info --}}
    <div class="flex-1 min-w-0">
        <p
            class="text-xs font-medium text-gray-800 leading-tight line-clamp-2 group-hover:text-teal-700 transition-colors">
            {{ $product->name }}
        </p>
        @if ($product->generic_name)
            <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $product->generic_name }}
                @if ($product->strength)
                    · {{ $product->strength }}
                @endif
            </p>
        @endif
        <div class="flex items-center gap-2 mt-1">
            <span class="text-sm font-black"
                style="color:var(--teal)">৳{{ number_format($product->effective_price, 2) }}</span>
            @if ($product->mrp && $product->mrp > $product->effective_price)
                <span class="text-xs text-gray-400 line-through">৳{{ number_format($product->mrp, 2) }}</span>
                <span class="text-[10px] font-bold bg-red-100 text-red-600 px-1.5 py-0.5 rounded-md">
                    -{{ $product->discount_percentage }}%
                </span>
            @endif
        </div>
    </div>

    {{-- Arrow --}}
    <i class="fas fa-chevron-right text-xs text-gray-300 group-hover:text-teal-500 flex-shrink-0 transition-colors"></i>
</a>
