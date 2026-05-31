@extends('layouts.shop')
@section('title', ($currentCat ? $currentCat->name . ' – ' : '') . 'Products')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-5">
        {{-- Product listing --}}
        <div>
            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <div>
                    <h1 class="font-bold text-gray-800">
                        {{ $currentCat ? $currentCat->icon . ' ' . $currentCat->name : 'All Products' }}
                    </h1>
                </div>
                <div class="flex items-center gap-2">
                    <select
                        onchange="window.location='{{ route('shop.index')}}?'+new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)),...{sort:this.value}}).toString()"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none focus:border-teal-500">
                        @foreach(['newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'discount' => 'Best Discount', 'top_selling' => 'Top Selling', 'rating' => 'Top Rated'] as $val => $label)
                            <option value="{{ $val }}" @selected($sort === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Active filter tags --}}
            @if(request()->hasAny(['q', 'category', 'brand', 'flash_sale', 'in_stock', 'no_rx', 'featured', 'min_price', 'max_price']))
                <div class="flex flex-wrap gap-1.5 mb-4">
                    @if(request('q'))
                        <span
                            class="flex items-center gap-1 bg-teal-100 text-teal-800 text-xs px-2.5 py-1 rounded-full font-medium">
                            Search: "{{ request('q') }}"
                            <a href="{{ route('shop.index', request()->except(['q', 'page'])) }}"
                                class="hover:text-teal-600">&times;</a>
                        </span>
                    @endif
                    @if(request('category') && $currentCat)
                        <span
                            class="flex items-center gap-1 bg-teal-100 text-teal-800 text-xs px-2.5 py-1 rounded-full font-medium">
                            {{ $currentCat->name }}
                            <a href="{{ route('shop.index', request()->except(['category', 'page'])) }}">&times;</a>
                        </span>
                    @endif
                    <a href="{{ route('shop.index') }}" class="text-xs text-red-600 hover:underline px-2 py-1">Clear all</a>
                </div>
            @endif

            {{-- Grid --}}
            @if($products->isEmpty())
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="font-bold text-gray-700 text-lg mb-2">No products found</h3>
                    <p class="text-gray-500 text-sm mb-4">Try different filters or search terms</p>
                    <a href="{{ route('shop.index') }}" class="btn-primary">View All Products</a>
                </div>
            @else
                <div class="products-grid">
                    @foreach($products as $product)
                        @include('shop.partials.product-card-grid', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $products->links('vendor.pagination.simple') }}
                </div>
            @endif
        </div>
    </div>
@endsection