@extends('layouts.shop')
@php
    // Canonical intentionally keeps only category/brand — those define a
    // genuinely different product set worth its own indexed URL. Sort,
    // price range, and pagination don't, so they're dropped here to avoid
    // near-duplicate URLs competing with each other in search results.
    $filterLabel = collect([$currentCat->name ?? null, $currentBrand->name ?? null])->filter()->implode(' – ');
@endphp
@section('title', ($filterLabel ? $filterLabel . ' – ' : '') . 'Products')
@section('meta_description',
    $currentCat->meta_description ?? ($filterLabel
        ? "Buy {$filterLabel} online in Bangladesh — genuine products, fast delivery, cash on delivery available."
        : 'Browse all medicine, healthcare and wellness products. Genuine products, fast delivery across Bangladesh.'))
@section('canonical', route('shop.index', array_filter(['category' => request('category'), 'brand' => request('brand')])))
@if($filterLabel)
    @section('og_title', $filterLabel)
    @section('og_image', $currentCat->banner_image ?? $currentBrand->logo ?? asset('favicon.svg'))
@endif

@push('head')
<script type="application/ld+json">
    {!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $filterLabel ?: 'All Products',
        'url' => route('shop.index', array_filter(['category' => request('category'), 'brand' => request('brand')])),
        'mainEntity' => [
            '@type' => 'ItemList',
            'itemListElement' => $products->getCollection()->values()->map(fn($p, $i) => [
                '@type' => 'ListItem',
                'position' => $i + 1,
                'url' => route('shop.product', $p->slug),
                'name' => $p->name,
            ])->all(),
        ],
    ]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@php
    // Drives the mobile filter button's count badge — same fields the
    // "active filter tags" row below already checks for, minus q/page which
    // aren't filters the drawer itself controls.
    $activeFilterCount = collect(['category','brand','flash_sale','in_stock','no_rx','featured','min_price','max_price'])
        ->filter(fn($k) => request()->filled($k))
        ->count();
@endphp
@section('content')
<div class="max-w-7xl mx-auto px-4 py-5">
    <div class="flex gap-5">

        {{-- Sidebar filters (desktop) --}}
        <aside class="hidden lg:block w-56 flex-shrink-0">
            @include('shop.partials.product-filters')
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
                    {{-- Filter drawer trigger — mobile/tablet only. Below lg,
                         the sidebar above is hidden with no other way to reach
                         price range, quick filters, or brands; this opens the
                         same content (see product-filters.blade.php) as a
                         slide-in drawer instead of just dropping it. --}}
                    <button type="button" onclick="toggleProductFilters()" class="filter-toggle-btn lg:hidden">
                        <i class="fas fa-sliders-h"></i> Filter
                        @if($activeFilterCount > 0)
                            <span class="count">{{ $activeFilterCount }}</span>
                        @endif
                    </button>
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

{{-- Mobile/tablet filter drawer — see the "Filter" button in the toolbar
     above. Same product-filters partial as the desktop sidebar. --}}
<div id="filter-drawer-overlay" class="filter-drawer-overlay lg:hidden" style="display:none" onclick="toggleProductFilters()"></div>
<aside id="product-filter-drawer" class="product-filter-drawer lg:hidden">
    <div class="product-filter-drawer-head">
        <p style="font-weight:800;font-size:15px;color:#1f2937;margin:0">Filters</p>
        <button onclick="toggleProductFilters()" aria-label="Close filters" class="mobile-cat-drawer-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="product-filter-drawer-body">
        @include('shop.partials.product-filters')
    </div>
</aside>
@endsection

@push('scripts')
<script>
function toggleProductFilters() {
    const drawer = document.getElementById('product-filter-drawer');
    const overlay = document.getElementById('filter-drawer-overlay');
    const open = drawer.classList.toggle('open');
    overlay.style.display = open ? 'block' : 'none';
    document.body.style.overflow = open ? 'hidden' : '';
}
</script>
@endpush

@if(request()->filled('q'))
    @php $pixelSearch = \App\Models\Setting::get('meta_pixel_search', 'true') === 'true'; @endphp
    @if($pixelSearch)
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.fbTrack) {
            window.fbTrack('Search', {
                search_string: @json(request('q')),
                content_category: 'product',
            });
        }
    });
    </script>
    @endpush
    @endif
@endif