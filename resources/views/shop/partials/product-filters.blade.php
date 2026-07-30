{{-- Shared between the desktop sidebar and the mobile filter drawer — see
     shop/products/index.blade.php. Kept as one partial so the two surfaces
     can never drift out of sync with each other. --}}

{{-- Price range --}}
<div class="bg-white rounded-xl border p-4 mb-4">
    <h3 class="font-bold text-gray-800 text-sm mb-3">Price Range</h3>
    <form method="GET" action="{{ route('shop.index') }}">
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
<div class="bg-white rounded-xl border p-4 space-y-2 mb-4">
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
