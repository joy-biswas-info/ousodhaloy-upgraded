@extends('layouts.shop')
@section('title', ($currentCat ? $currentCat->name . ' – ' : '') . 'Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-5">
    <div class="flex gap-5">

        {{-- Sidebar filters (desktop) --}}
        <aside class="hidden lg:block w-56 flex-shrink-0 space-y-4">

            {{-- Price range --}}
            <div class="bg-white rounded-xl border p-4">
                <h3 class="font-bold text-gray-800 text-sm mb-3">Price Range</h3>
                <form method="GET" action="{{ route('shop.index') }}" id="price-filter">
                    @foreach(request()->except(['min_price','max_price','page']) as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <div class="flex gap-2 mb-2">
                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}"
                            class="w-1/2 border border-gray-200 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-teal-500">
                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}"
                            class="w-1/2 border border-gray-200 rounded-lg px-2 py-1.5 text-xs outline-none focus:border-teal-500">
                    </div>
                    <button type="submit" class="w-full btn-secondary btn-sm">Apply</button>
                </form>
            </div>

            {{-- Quick filters --}}
            <div class="bg-white rounded-xl border p-4 space-y-2">
                <h3 class="font-bold text-gray-800 text-sm mb-3">Filters</h3>
                @foreach([
                    ['key' => 'flash_sale', 'label' => '⚡ Flash Sale', 'value' => '1'],
                    ['key' => 'in_stock',   'label' => '✅ In Stock',   'value' => '1'],
                    ['key' => 'no_rx',      'label' => '💊 No Rx Needed','value' => '1'],
                    ['key' => 'featured',   'label' => '⭐ Featured',    'value' => '1'],
                ] as $f)
                <a href="{{ route('shop.index', array_merge(request()->except([$f['key'],'page']), request($f['key']) ? [] : [$f['key'] => $f['value']])) }}"
                    class="flex items-center gap-2 text-xs px-2 py-1.5 rounded-lg transition-colors {{ request($f['key']) ? 'bg-teal-50 text-teal-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="w-3 h-3 border rounded {{ request($f['key']) ? 'bg-teal-600 border-teal-600' : 'border-gray-300' }} flex items-center justify-center">
                        @if(request($f['key']))<i class="fas fa-check text-white" style="font-size:8px"></i>@endif
                    </span>
                    {{ $f['label'] }}
                </a>
                @endforeach
            </div>

            {{-- Brands --}}
            @if($brands->count() > 0)
            <div class="bg-white rounded-xl border p-4">
                <h3 class="font-bold text-gray-800 text-sm mb-3">Brands</h3>
                <div class="space-y-1 max-h-48 overflow-y-auto">
                    @foreach($brands as $brand)
                    <a href="{{ route('shop.index', array_merge(request()->except(['brand','page']), ['brand' => $brand->slug])) }}"
                        class="flex items-center gap-2 text-xs px-2 py-1.5 rounded-lg transition-colors {{ request('brand') === $brand->slug ? 'text-teal-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="w-3 h-3 border rounded {{ request('brand') === $brand->slug ? 'bg-teal-600 border-teal-600' : 'border-gray-300' }}"></span>
                        {{ $brand->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>

        {{-- Product listing --}}
        <div class="flex-1 min-w-0">

            {{-- Toolbar --}}
            <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                <div>
                    <h1 class="font-bold text-gray-800">{{ $currentCat ? $currentCat->icon.' '.$currentCat->name : 'All Products' }}</h1>
                    <p class="text-xs text-gray-500">{{ $products->total() }} products found</p>
                </div>
                <div class="flex items-center gap-2">
                    <select onchange="window.location='{{ route('shop.index') }}?'+new URLSearchParams({...Object.fromEntries(new URLSearchParams(window.location.search)),...{sort:this.value}}).toString()"
                        class="border border-gray-200 rounded-lg px-3 py-2 text-xs outline-none focus:border-teal-500">
                        @foreach(['newest' => 'Newest', 'price_asc' => 'Price: Low to High', 'price_desc' => 'Price: High to Low', 'discount' => 'Best Discount', 'top_selling' => 'Top Selling', 'rating' => 'Top Rated'] as $val => $label)
                        <option value="{{ $val }}" @selected($sort === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Active filter tags --}}
            @if(request()->hasAny(['q','category','brand','flash_sale','in_stock','no_rx','featured','min_price','max_price']))
            <div class="flex flex-wrap gap-1.5 mb-4">
                @if(request('q'))
                <span class="flex items-center gap-1 bg-teal-100 text-teal-800 text-xs px-2.5 py-1 rounded-full font-medium">
                    Search: "{{ request('q') }}"
                    <a href="{{ route('shop.index', request()->except(['q','page'])) }}" class="hover:text-teal-600">&times;</a>
                </span>
                @endif
                @if(request('category') && $currentCat)
                <span class="flex items-center gap-1 bg-teal-100 text-teal-800 text-xs px-2.5 py-1 rounded-full font-medium">
                    {{ $currentCat->name }}
                    <a href="{{ route('shop.index', request()->except(['category','page'])) }}">&times;</a>
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
</div>
@endsection