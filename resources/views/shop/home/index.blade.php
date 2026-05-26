@extends('layouts.shop')
@section('title', \App\Models\Setting::get('site_name','Ousodhaloy') . ' – বাংলাদেশের বিশ্বস্ত অনলাইন ফার্মেসি')

@section('content')

{{-- ── Hero Slider ──────────────────────────────────────────────────────── --}}
<section x-data="heroSlider({{ $banners->count() }})" style="position:relative;overflow:hidden;background:var(--teal-dark)">
    @php $heroH = (int)\App\Models\Setting::get('hero_banner_height', 400); @endphp
    @if($banners->count() > 1)
    <div style="position:relative;min-height:240px;height:{{ $heroH }}px;max-height:{{ $heroH }}px;">

        @forelse($banners as $i => $banner)
        <div x-show="current === {{ $i }}"
            x-transition:enter="transition-opacity duration-500"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            style="position:absolute;inset:0;background:{{ $banner->bg_color }}">

            @if($banner->image_url)
            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                style="width:100%;height:100%;object-fit:cover;display:block">
            <div style="position:absolute;inset:0;background:linear-gradient(to right,rgba(0,0,0,.55) 0%,rgba(0,0,0,.15) 55%,transparent 100%)"></div>
            @endif

            <div style="position:absolute;inset:0;display:flex;align-items:center;padding:0 32px">
                <div style="color:#fff;max-width:480px">
                    @if($banner->badge_text)
                    <span style="display:inline-block;background:rgba(255,255,255,.2);font-size:11px;font-weight:600;padding:4px 12px;border-radius:20px;margin-bottom:10px">{{ $banner->badge_text }}</span>
                    @endif
                    <h1 style="font-size:clamp(20px,4vw,36px);font-weight:900;line-height:1.2;margin-bottom:8px;text-shadow:0 1px 4px rgba(0,0,0,.3)">{{ $banner->title }}</h1>
                    @if($banner->subtitle)
                    <p style="font-size:14px;opacity:.88;margin-bottom:16px;text-shadow:0 1px 3px rgba(0,0,0,.2)">{{ $banner->subtitle }}</p>
                    @endif
                    @if($banner->link_url)
                    <a href="{{ $banner->link_url }}" style="display:inline-flex;align-items:center;gap:8px;background:#fff;color:var(--teal-dark);font-weight:700;font-size:13px;padding:10px 22px;border-radius:10px;text-decoration:none;transition:transform .15s" onmouseover="this.style.transform='scale(1.04)'" onmouseout="this.style.transform=''">
                        {{ $banner->button_text ?? 'Shop Now' }} <i class="fas fa-arrow-right" style="font-size:11px"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @empty
        @endforelse
    </div>
    <button @click="prev()" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.3);color:#fff;width:34px;height:34px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s" onmouseover="this.style.background='rgba(0,0,0,.55)'" onmouseout="this.style.background='rgba(0,0,0,.3)'">
        <i class="fas fa-chevron-left" style="font-size:12px"></i>
    </button>
    <button @click="next()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:rgba(0,0,0,.3);color:#fff;width:34px;height:34px;border-radius:50%;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:background .15s" onmouseover="this.style.background='rgba(0,0,0,.55)'" onmouseout="this.style.background='rgba(0,0,0,.3)'">
        <i class="fas fa-chevron-right" style="font-size:12px"></i>
    </button>
    <div style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);display:flex;gap:6px">
        @foreach($banners as $i => $b)
        <button @click="current={{ $i }}"
            :style="current==={{ $i }} ? 'width:20px;background:#fff;opacity:1' : 'width:6px;background:#fff;opacity:.5'"
            style="height:6px;border-radius:3px;border:none;cursor:pointer;transition:all .3s"></button>
        @endforeach
    </div>
    @endif
</section>
{{-- ── Promo banners ───────────────────────────────────────────────────── --}}
@if($promoBanners->count() > 0)
<div style="padding:16px 16px 0">
    <div style="display:grid;grid-template-columns:repeat({{ min($promoBanners->count(),3) }},1fr);gap:10px">
        @foreach($promoBanners as $b)
        <a href="{{ $b->link_url ?? '#' }}"
            style="border-radius:12px;overflow:hidden;display:flex;align-items:center;padding:16px;gap:12px;min-height:80px;text-decoration:none;background:{{ $b->bg_color }};transition:opacity .15s"
            onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
            <div style="color:#fff;flex:1">
                <p style="font-weight:900;font-size:15px;line-height:1.3;margin:0">{{ $b->title }}</p>
                @if($b->subtitle)<p style="font-size:11px;opacity:.8;margin:3px 0 0">{{ $b->subtitle }}</p>@endif
                @if($b->button_text)<span style="display:inline-block;margin-top:8px;font-size:11px;font-weight:700;background:rgba(255,255,255,.2);padding:3px 10px;border-radius:20px">{{ $b->button_text }} →</span>@endif
            </div>
            @if($b->image_url)<img src="{{ $b->image_url }}" style="height:56px;object-fit:contain" alt="{{ $b->title }}">@endif
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ── Flash Sale ──────────────────────────────────────────────────────── --}}
@if($flashSale->count() > 0)
<div style="padding:16px">
    <div style="border-radius:14px;overflow:hidden;background:linear-gradient(135deg,#ea580c,#dc2626)">
        <div style="padding:12px 16px;display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:12px">
                <span style="background:#fff;color:#ea580c;font-weight:900;font-size:13px;padding:6px 12px;border-radius:8px">⚡ FLASH SALE</span>
                @if($flashDeal)
                <div style="display:flex;align-items:center;gap:4px;color:#fff;font-size:13px;font-weight:700"
                    x-data="countdown('{{ $flashDeal->ends_at->toISOString() }}')">
                    <span>Ends in:</span>
                    <span style="background:rgba(0,0,0,.25);padding:2px 6px;border-radius:4px;font-family:monospace" x-text="hours"></span>
                    <span style="color:rgba(255,255,255,.7)">:</span>
                    <span style="background:rgba(0,0,0,.25);padding:2px 6px;border-radius:4px;font-family:monospace" x-text="minutes"></span>
                    <span style="color:rgba(255,255,255,.7)">:</span>
                    <span style="background:rgba(0,0,0,.25);padding:2px 6px;border-radius:4px;font-family:monospace" x-text="seconds"></span>
                </div>
                @endif
            </div>
            <a href="{{ route('shop.index', ['flash_sale' => 1]) }}" style="color:#fff;font-size:12px;font-weight:600;text-decoration:none">See all →</a>
        </div>
        <div class="scrollbar-hide" style="display:flex;gap:10px;overflow-x:auto;padding:0 16px 16px">
            @foreach($flashSale as $product)
            @include('shop.partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ── Featured Products ───────────────────────────────────────────────── --}}
@if($featured->count() > 0)
<div style="padding:8px 16px 16px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h2 style="font-size:15px;font-weight:900;color:#1f2937;margin:0">⭐ Featured Products</h2>
        <a href="{{ route('shop.index', ['featured' => 1]) }}" style="font-size:12px;font-weight:600;color:var(--teal);text-decoration:none">View all →</a>
    </div>
    <div class="products-grid">
        @foreach($featured->take(10) as $product)
        @include('shop.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</div>
@endif

{{-- ── New Arrivals ────────────────────────────────────────────────────── --}}
@if($newArrivals->count() > 0)
<div style="padding:8px 16px 24px">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
        <h2 style="font-size:15px;font-weight:900;color:#1f2937;margin:0">🆕 New Arrivals</h2>
        <a href="{{ route('shop.index') }}" style="font-size:12px;font-weight:600;color:var(--teal);text-decoration:none">View all →</a>
    </div>
    <div class="products-grid">
        @foreach($newArrivals->take(10) as $product)
        @include('shop.partials.product-card', ['product' => $product])
        @endforeach
    </div>
</div>
@endif

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