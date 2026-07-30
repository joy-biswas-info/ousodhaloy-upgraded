@extends('layouts.shop')
@section('title', \App\Models\Setting::get('site_name','Ousodhaloy') . ' – বাংলাদেশের বিশ্বস্ত অনলাইন ফার্মেসি')

@section('content')
<div class="max-w-8xl mx-auto">

{{-- ── Hero Slider ──────────────────────────────────────────────────────────
     Renders for 1+ banners (previously required 2+, so a site with a single
     hero banner configured — the common case — showed nothing at all here).
     Slider controls (arrows/dots) only appear once there's actually more
     than one slide to move between. ── --}}
@if($banners->count() > 0)
<section class="home-hero" x-data="heroSlider({{ $banners->count() }})">
    @php $heroH = (int)\App\Models\Setting::get('hero_banner_height', 400); @endphp
    <div class="home-hero-viewport" style="height:{{ $heroH }}px;max-height:{{ $heroH }}px">
        @foreach($banners as $i => $banner)
        <div class="home-hero-slide" x-show="current === {{ $i }}"
            x-transition:enter="transition-opacity duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            style="background:{{ $banner->bg_color }}">

            @if($banner->image_url)
            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}">
            <div class="home-hero-overlay"></div>
            @endif

            <div class="home-hero-content-wrap">
                <div class="home-hero-content">
                    @if($banner->badge_text)
                    <span class="home-hero-badge">{{ $banner->badge_text }}</span>
                    @endif
                    <h1 class="home-hero-title">{{ $banner->title }}</h1>
                    @if($banner->subtitle)
                    <p class="home-hero-subtitle">{{ $banner->subtitle }}</p>
                    @endif
                    @if($banner->link_url)
                    <a href="{{ $banner->link_url }}" class="home-hero-cta">
                        {{ $banner->button_text ?? 'Shop Now' }} <i class="fas fa-arrow-right" style="font-size:11px"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($banners->count() > 1)
    <button @click="prev()" class="home-hero-arrow prev" aria-label="Previous slide">
        <i class="fas fa-chevron-left" style="font-size:12px"></i>
    </button>
    <button @click="next()" class="home-hero-arrow next" aria-label="Next slide">
        <i class="fas fa-chevron-right" style="font-size:12px"></i>
    </button>
    <div class="home-hero-dots">
        @foreach($banners as $i => $b)
        <button @click="current={{ $i }}" aria-label="Go to slide {{ $i + 1 }}"
            :style="current==={{ $i }} ? 'width:20px;opacity:1' : 'width:6px;opacity:.5'"></button>
        @endforeach
    </div>
    @endif
</section>
@endif

{{-- ── Promo banners ───────────────────────────────────────────────────── --}}
@if($promoBanners->count() > 0)
<div class="home-promo-grid" style="--promo-cols:{{ min($promoBanners->count(), 3) }}">
    @foreach($promoBanners as $b)
    <a href="{{ $b->link_url ?? '#' }}" class="home-promo-card" style="background:{{ $b->bg_color }}">
        <div class="home-promo-info">
            <p>{{ $b->title }}</p>
            @if($b->subtitle)<p>{{ $b->subtitle }}</p>@endif
            @if($b->button_text)<span class="home-promo-badge">{{ $b->button_text }} →</span>@endif
        </div>
        @if($b->image_url)<img src="{{ $b->image_url }}" alt="{{ $b->title }}">@endif
    </a>
    @endforeach
</div>
@endif

{{-- ── Shop by Category ────────────────────────────────────────────────── --}}
@if($categories->count() > 0)
<div class="home-section">
    <div class="home-section-head">
        <h2 class="home-section-title">🗂️ Shop by Category</h2>
    </div>
    <div class="home-category-grid">
        @foreach($categories as $cat)
        <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" class="home-category-card">
            <div class="home-category-icon">{{ $cat->icon ?: '💊' }}</div>
            <p class="home-category-name">{{ $cat->name }}</p>
            <p class="home-category-count">{{ $cat->products_count }} items</p>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ── Flash Sale ──────────────────────────────────────────────────────── --}}
@if($flashSale->count() > 0)
<div class="home-section" style="padding-top:4px">
    <div class="home-flash-sale">
        <div class="home-flash-head">
            <div class="home-flash-left">
                <span class="home-flash-badge">⚡ FLASH SALE</span>
                @if($flashDeal)
                <div class="home-flash-countdown" x-data="countdown('{{ $flashDeal->ends_at->toISOString() }}')">
                    <span>Ends in:</span>
                    <span class="home-flash-timer-unit" x-text="hours"></span>
                    <span style="color:rgba(255,255,255,.7)">:</span>
                    <span class="home-flash-timer-unit" x-text="minutes"></span>
                    <span style="color:rgba(255,255,255,.7)">:</span>
                    <span class="home-flash-timer-unit" x-text="seconds"></span>
                </div>
                @endif
            </div>
            <a href="{{ route('shop.index', ['flash_sale' => 1]) }}" class="home-flash-viewall">See all →</a>
        </div>
        <div class="home-flash-products scrollbar-hide products-grid" style="overflow-x:auto">
            @foreach($flashSale as $product)
            @include('shop.partials.product-card-grid', ['product' => $product])
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ── Featured Products ───────────────────────────────────────────────── --}}
@if($featured->count() > 0)
<div class="home-section">
    <div class="home-section-head">
        <h2 class="home-section-title">⭐ Featured Products</h2>
        <a href="{{ route('shop.index', ['featured' => 1]) }}" class="home-section-viewall">View all →</a>
    </div>
    <div class="products-grid">
        @foreach($featured->take(10) as $product)
        @include('shop.partials.product-card-grid', ['product' => $product])
        @endforeach
    </div>
</div>
@endif

{{-- ── Top Selling ─────────────────────────────────────────────────────── --}}
@if($topSelling->count() > 0)
<div class="home-section">
    <div class="home-section-head">
        <h2 class="home-section-title">🔥 Top Selling</h2>
        <a href="{{ route('shop.index', ['sort' => 'top_selling']) }}" class="home-section-viewall">View all →</a>
    </div>
    <div class="products-grid">
        @foreach($topSelling->take(10) as $product)
        @include('shop.partials.product-card-grid', ['product' => $product])
        @endforeach
    </div>
</div>
@endif

{{-- ── New Arrivals ────────────────────────────────────────────────────── --}}
@if($newArrivals->count() > 0)
<div class="home-section" style="padding-top:0">
    <div class="home-section-head">
        <h2 class="home-section-title">🆕 New Arrivals</h2>
        <a href="{{ route('shop.index') }}" class="home-section-viewall">View all →</a>
    </div>
    <div class="products-grid">
        @foreach($newArrivals->take(10) as $product)
        @include('shop.partials.product-card-grid', ['product' => $product])
        @endforeach
    </div>
</div>
@endif

</div>
@endsection

@push('scripts')
<script>
function heroSlider(count) {
    return {
        current: 0, count,
        init() { if (count > 1) setInterval(() => this.next(), 5000); },
        next() { this.current = (this.current + 1) % this.count; },
        prev() { this.current = (this.current - 1 + this.count) % this.count; },
    };
}
function countdown(endTime) {
    return {
        hours:'00', minutes:'00', seconds:'00',
        init() {
            const tick = () => {
                const d = new Date(endTime) - new Date();
                if (d <= 0) { this.hours = this.minutes = this.seconds = '00'; return; }
                this.hours   = String(Math.floor(d/3600000)).padStart(2,'0');
                this.minutes = String(Math.floor((d%3600000)/60000)).padStart(2,'0');
                this.seconds = String(Math.floor((d%60000)/1000)).padStart(2,'0');
            };
            tick(); setInterval(tick, 1000);
        }
    };
}
</script>
@endpush
