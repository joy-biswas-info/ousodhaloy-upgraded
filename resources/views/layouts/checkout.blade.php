<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ \App\Models\Setting::get('brand_primary', '#0e7673') }}">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', config('app.name', 'Ousodhaloy')) – Bangladesh's Trusted Online Healthcare and Wellness Shop</title>
    <meta name="description" content="@yield('meta_description', 'Buy genuine medicine, healthcare and wellness products online. Fast delivery across Bangladesh.')">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $bp = \App\Models\Setting::get('brand_primary', '#0e7673');
        $bd = \App\Models\Setting::get('brand_dark', '#0a5250');
        $bl = \App\Models\Setting::get('brand_light', '#13a09c');
        $bbg = \App\Models\Setting::get('brand_bg', '#e6f4f4');
    @endphp

    {{-- Inject dynamic brand colors as CSS variables --}}
    <style>
        :root {
            --teal: {{ $bp }};
            --teal-dark: {{ $bd }};
            --teal-light: {{ $bl }};
            --teal-bg: {{ $bbg }};
        }
    </style>
    @stack('styles')
    @include('partials.meta-pixel')
</head>

<body>
    {{-- Flash messages --}}
    @foreach (['success' => ['bg' => 'var(--teal)', 'icon' => 'check-circle'], 'error' => ['bg' => '#dc2626', 'icon' => 'exclamation-circle'], 'info' => ['bg' => '#2563eb', 'icon' => 'info-circle']] as $type => $cfg)
        @if (session($type))
            <div id="flash-{{ $type }}" class="animate-slide-in"
                style="position:fixed;top:16px;right:16px;z-index:9999;background:{{ $cfg['bg'] }};color:#fff;padding:12px 18px;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.2);display:flex;align-items:center;gap:8px;font-size:13px;font-weight:600;max-width:340px">
                <i class="fas fa-{{ $cfg['icon'] }}"></i>
                <span>{{ session($type) }}</span>
                <button onclick="this.parentElement.remove()"
                    style="margin-left:8px;opacity:.7;background:none;border:none;color:#fff;cursor:pointer;font-size:16px;line-height:1">&times;</button>
            </div>
        @endif
    @endforeach

    {{-- Minimal checkout header — logo only. No search, no category nav, no
         account menu: every one of those is an invitation to leave this page
         mid-order, which is exactly what a checkout flow shouldn't offer. The
         logo stays as a link (standard, low-temptation escape hatch), and a
         small lock badge reinforces that this is the secure, final step. --}}
    <header style="background:var(--teal);padding:14px 16px;display:flex;align-items:center;justify-content:space-between">
        <a href="{{ route('home') }}" style="display:flex;align-items:center;text-decoration:none;color:#fff;font-weight:900;font-size:18px">
            ঔষ<span style="color:#f87171">ধা</span>লয়
        </a>
        <div style="display:flex;align-items:center;gap:6px;color:rgba(255,255,255,.85);font-size:12px;font-weight:600">
            <i class="fas fa-lock"></i> Secure Checkout
        </div>
    </header>

    <main style="overflow-x:auto; min-height:70vh;">
        @yield('content')
    </main>

    {{-- Minimal footer — trust signals and required legal links only. No
         "Home / All Products / Track Order" quick links here on purpose;
         those belong on the storefront layout, not on the page whose only
         job is to finish this specific order. --}}
    <footer style="background:#111827;color:#9ca3af;padding:20px 16px;margin-top:24px">
        <div style="display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:18px;margin-bottom:16px">
            @foreach ([['fas fa-truck', 'Fast Delivery · 24-48hrs'], ['fas fa-headset', '24/7 Support'], ['fas fa-shield-alt', 'Secure Payment']] as [$icon, $text])
                <div style="display:flex;align-items:center;gap:6px;font-size:11px;font-weight:600;color:#e5e7eb">
                    <i class="{{ $icon }}" style="color:var(--teal-light)"></i> {{ $text }}
                </div>
            @endforeach
        </div>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:8px 16px;border-top:1px solid #1f2937;padding-top:14px;font-size:11px">
            <span>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }} Ltd.</span>
            <span>·</span>
            <a href="{{ route('legal.privacy') }}" style="color:#9ca3af;text-decoration:none">Privacy Policy</a>
            <a href="{{ route('legal.returns') }}" style="color:#9ca3af;text-decoration:none">Return Policy</a>
            <span>·</span>
            <span><i class="fas fa-phone" style="color:var(--teal-light);margin-right:4px"></i>{{ \App\Models\Setting::get('site_phone', '09610016778') }}</span>
        </div>
    </footer>

    @include('partials.cookie-banner')

    <script>
        setTimeout(() => document.querySelectorAll('[id^=flash-]').forEach(el => el.remove()), 4000);
    </script>
    @stack('scripts')
</body>

</html>
