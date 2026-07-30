<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ \App\Models\Setting::get('brand_primary', '#0e7673') }}">
    <title>@yield('title', config('app.name', 'Ousodhaloy')) – Bangladesh's Trusted Online Healthcare and Wellness Shop</title>
    <meta name="description" content="@yield('meta_description', 'Buy genuine medicine, healthcare and wellness products online. Fast delivery across Bangladesh.')">
    {{-- Canonical defaults to the current path with no query string, so
         filtered/sorted listing URLs (?min_price=, ?sort=, etc) all point
         back at the one clean URL instead of each being indexed separately. --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">
    <meta property="og:site_name" content="{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', config('app.name', 'Ousodhaloy'))">
    <meta property="og:description" content="@yield('meta_description', 'Buy genuine medicine, healthcare and wellness products online. Fast delivery across Bangladesh.')">
    <meta property="og:image" content="@yield('og_image', asset('favicon.svg'))">
    <meta property="og:url" content="@yield('canonical', url()->current())">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="manifest" href="/manifest.json">

    {{-- Preconnect to every third-party origin this page loads from, so the
         DNS/TLS handshake happens during the preload scan instead of only
         starting once the browser reaches each <link>/<script> tag. --}}
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Hind+Siliguri:wght@400;500;600;700&display=swap">

    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $bp = \App\Models\Setting::get('brand_primary', '#0e7673');
        $bd = \App\Models\Setting::get('brand_dark', '#0a5250');
        $bl = \App\Models\Setting::get('brand_light', '#13a09c');
        $bbg = \App\Models\Setting::get('brand_bg', '#e6f4f4');
        $messengerUrl = \App\Models\Setting::get('messenger_url', '');
        $siteLogo = \App\Models\Setting::get('site_logo');
        $siteName = \App\Models\Setting::get('site_name', 'Ousodhaloy');
    @endphp

    {{-- Inject dynamic brand colors as CSS variables --}}
    <style>
        :root {
            --teal:
                {{ $bp }};
            --teal-dark:
                {{ $bd }};
            --teal-light:
                {{ $bl }};
            --teal-bg:
                {{ $bbg }};
        }

        #shop-sidebar.mobile-open {
            transform: translateX(0) !important;
        }
    </style>
    @stack('styles')

    {{-- Organization schema — site-wide, helps Google associate search
         results and the knowledge panel with the actual business. --}}
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => \App\Models\Setting::get('site_name', 'Ousodhaloy'),
            'url' => url('/'),
            'logo' => asset('favicon.svg'),
            'telephone' => \App\Models\Setting::get('site_phone', '09610016778'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => \App\Models\Setting::get('site_address', 'Dhaka, Bangladesh'),
                'addressCountry' => 'BD',
            ],
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>
    @stack('head')
    @include('partials.meta-pixel')
</head>

<body class="">
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
    {{-- ═══════════════════════════════════════════════════════════
    MOBILE HEADER ≤ 1023px
    Row 1 : Hamburger | Logo | Cart | Account
    Row 2 : Full-width search bar
    ═══════════════════════════════════════════════════════════════ --}}
    <header class="mobile-header lg:hidden">
        <div x-data="{ userMenu: false }">

            {{-- ── Row 1 ── --}}
            <div class="mobile-header-row">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="hdr-logo">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}">
                    @else
                        <span class="hdr-logo-text">
                        ঔষ<span class="accent">ধা</span>লয়
                        </span>
                    @endif
                </a>

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="hdr-icon-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span id="cart-count-mobile" class="hdr-icon-badge"
                        style="display:{{ \App\Http\Controllers\Shop\CartController::getCount() === 0 ? 'none' : 'flex' }}">
                        {{ \App\Http\Controllers\Shop\CartController::getCount() }}
                    </span>
                </a>

                {{-- Account --}}
                <div style="position:relative;flex-shrink:0">
                    <button @click="userMenu=!userMenu" class="hdr-icon-btn">
                        <i class="fas fa-user"></i>
                    </button>
                    @include('partials.account-dropdown')
                </div>
            </div>

            {{-- ── Row 2: Search ── --}}
            <div class="mobile-search-row" x-data="liveSearch()" @click.away="open=false">
                <div class="mobile-search-box" style="position:relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" x-model="query" @input.debounce.280ms="search()"
                        @focus="if(results.length) open=true" @keydown.enter="goToShop()" @keydown.escape="open=false"
                        placeholder="Search medicines, brands...">
                    <button class="search-btn" @click="goToShop()">
                        <i class="fas fa-search"></i>
                    </button>
                    @include('partials.search-dropdown')
                </div>
            </div>
        </div>
    </header>

    {{-- ── Desktop HEADER ─── --}}
    <header class="site-header hidden lg:block px-4 my-auto">
        <div class="header-inner" x-data="{ userMenu: false }">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="hdr-logo">
                @if($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" style="height:40px">
                @else
                    <span class="hdr-logo-text" style="font-size:24px">
                    ঔষ<span class="accent">ধা</span>লয়
                    </span>
                @endif
            </a>

            {{-- Search bar — centred --}}
            <div class="header-search" x-data="liveSearch()" @click.away="open=false" style="margin:0 auto;">
                <i class="fas fa-search" style="color:#9ca3af;padding-left:12px;padding-right:4px;font-size:13px;"></i>
                <input type="text" x-model="query" @input.debounce.280ms="search()"
                    @focus="if(results.length) open=true" @keydown.enter="goToShop()" @keydown.escape="open=false"
                    placeholder="Search medicines, brands...">
                <button class="search-btn" @click="goToShop()">
                    <span class="hidden-mobile">Search</span>
                    <i class="fas fa-arrow-right visible-mobile" style="display:none"></i>
                </button>
                @include('partials.search-dropdown')
            </div>

            {{-- Right actions — desktop --}}
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;" class="desktop-actions">
                {{-- Upload Rx --}}
                <a href="{{ route('checkout.index') }}" class="hdr-action" style="display:none" id="rx-btn">
                    <i class="fas fa-file-prescription"></i>
                    <span style="font-size:11px">Upload Rx</span>
                </a>

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}" class="hdr-action" style="position:relative">
                    <i class="fas fa-shopping-cart"></i>
                    <span style="font-size:11px" class="hidden-mobile">Cart</span>
                    <span id="cart-count" class="badge"
                        style="{{ \App\Http\Controllers\Shop\CartController::getCount() === 0 ? 'display:none' : '' }}">
                        {{ \App\Http\Controllers\Shop\CartController::getCount() }}
                    </span>
                </a>

                {{-- Account --}}
                <div style="position:relative">
                    <button @click="userMenu=!userMenu" class="hdr-action">
                        <i class="fas fa-user"></i>
                        <span style="font-size:11px"
                            class="hidden-mobile">{{ auth()->user()?->name ? Str::words(auth()->user()->name, 1, '') : 'Login' }}</span>
                        <i class="fas fa-chevron-down" style="font-size:9px"></i>
                    </button>
                    @include('partials.account-dropdown')
                </div>
            </div>
        </div>
    </header>
    <div class="subnav">
        <div class="subnav-inner max-w-8xl mx-auto">
            <a href="{{ route('shop.index') }}"
                class="snav-item {{ request()->routeIs('shop.index') && !request()->has('category') ? 'active' : '' }}">
                🏠 All
            </a>
            @foreach (\App\Models\Category::active()->get() as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}"
                    class="snav-item {{ request('category') === $cat->slug ? 'active' : '' }}">
                    {{ $cat->icon }} {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- ── BODY: sidebar + full-width content ────────────────────────── --}}
    {{-- MAIN CONTENT — full remaining width --}}
    <main style="overflow-x:auto; margin: 0 auto; padding: 0 20px; min-height: 80vh;">
        @yield('content')
    </main>

    {{-- Sidebar overlay (mobile) --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:199">
    </div>

    {{-- Mobile category drawer — opened by "Categories" in the bottom nav.
         Mirrors the desktop subnav's category list so there's one source
         of truth for which categories are shown. --}}
    <aside id="shop-sidebar" class="lg:hidden mobile-cat-drawer">
        <div class="mobile-cat-drawer-head">
            <p style="font-weight:800;font-size:15px;color:#1f2937;margin:0">Categories</p>
            <button onclick="toggleSidebar()" aria-label="Close categories" class="mobile-cat-drawer-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav style="padding:8px">
            <a href="{{ route('shop.index') }}" onclick="toggleSidebar()"
                class="mobile-cat-drawer-link {{ request()->routeIs('shop.index') && !request()->has('category') ? 'active' : '' }}">
                🏠 All Products
            </a>
            @foreach (\App\Models\Category::active()->get() as $cat)
                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" onclick="toggleSidebar()"
                    class="mobile-cat-drawer-link {{ request('category') === $cat->slug ? 'active' : '' }}">
                    {{ $cat->icon }} {{ $cat->name }}
                </a>
            @endforeach
        </nav>
    </aside>
    {{-- ── FOOTER ── --}}
    <footer class="site-footer">
        <div class="footer-trust-strip">
            <div class="footer-trust-strip-inner">
                @foreach ([['fas fa-truck', 'Fast Delivery', '24-48hrs'], ['fas fa-headset', '24/7 Support', 'Always here'], ['fas fa-shield-alt', 'Secure Pay', 'bKash · Card']] as [$icon, $text, $sub])
                    <div class="footer-trust-item">
                        <i class="{{ $icon }}"></i>
                        <div>
                            <p>{{ $text }}</p>
                            <p>{{ $sub }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="footer-main">
            <div class="footer-grid">
                <div class="footer-brand-col">
                    <div class="footer-brand-logo">
                        @if($siteLogo)
                            <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}">
                        @else
                            <span class="hdr-logo-text" style="font-size:18px">
                            ঔষ<span class="accent">ধা</span>লয়
                            </span>
                        @endif
                    </div>
                    <p class="footer-brand-text">Trusted Health and Wellness Shop in Bangladesh</p>
                </div>
                <div>
                    <p class="footer-col-title">Quick Links</p>
                    @foreach ([['home', 'Home'], ['shop.index', 'All Products'], ['track', 'Track Order'], ['auth.login', 'My Account'], ['legal.privacy', 'Privacy Policy'], ['legal.terms', 'Terms'], ['legal.returns', 'Return Policy']] as [$rt, $lb])
                        <a href="{{ route($rt) }}" class="footer-link">{{ $lb }}</a>
                    @endforeach
                </div>
                <div>
                    <p class="footer-col-title">Contact</p>
                    <p class="footer-contact-line"><i class="fas fa-phone"></i>{{ \App\Models\Setting::get('site_phone', '09610016778') }}</p>
                    <p class="footer-contact-line"><i class="fas fa-envelope"></i>{{ \App\Models\Setting::get('site_email', 'info@ousodhaloy.com') }}</p>
                    <p class="footer-contact-line"><i class="fas fa-map-marker-alt"></i>{{ \App\Models\Setting::get('site_address', 'Dhaka, Bangladesh') }}</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }} Ltd. All rights reserved.</p>
                <div class="footer-ssl-badge">
                    <i class="fas fa-lock"></i><span>SSL Secured</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- ── MESSENGER FAB ────────────────────────────────────────────────── --}}
    @if ($messengerUrl)
        <a href="{{ $messengerUrl }}" target="_blank" rel="noopener" class="messenger-fab"
            aria-label="Chat on Messenger">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="34" cy="34" r="34" fill="{{ $bp }}" />
                <path
                    d="M24 10C16.268 10 10 15.82 10 23c0 3.876 1.748 7.354 4.558 9.826V37l4.242-2.334A15.16 15.16 0 0024 35c7.732 0 14-5.82 14-12S31.732 10 24 10z"
                    fill="white" />
                <path d="M13 26l6-6.4 4.5 4.5L30 20l-6.2 6.6-4.3-4.5L13 26z" fill="{{ $bp }}" />
            </svg>
        </a>
    @endif

    {{-- ── MOBILE BOTTOM NAV ────────────────────────────────────────────── --}}
    <nav class="mobile-bottom-nav">
        <div class="nav-items">
            <a href="{{ route('home') }}" class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Home</span>
            </a>
            <button class="nav-item" onclick="toggleSidebar()">
                <i class="fas fa-th-large"></i><span>Categories</span>
            </button>
            <a href="{{ route('cart.index') }}"
                class="nav-item cart-btn {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                @if (\App\Http\Controllers\Shop\CartController::getCount() > 0)
                    <span class="badge">{{ \App\Http\Controllers\Shop\CartController::getCount() }}</span>
                @endif
                <span>Cart</span>
            </a>
            <a href="{{ route('account.orders') }}"
                class="nav-item {{ request()->routeIs('account.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i><span>Orders</span>
            </a>
            <a href="{{ auth()->check() ? route('account.profile') : route('auth.login') }}"
                class="nav-item {{ request()->routeIs('auth.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i><span>{{ auth()->check() ? 'Account' : 'Login' }}</span>
            </a>
        </div>
    </nav>
    {{-- Back to top --}}
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" class="back-to-top-btn"
        style="display:none">
        <i class="fas fa-arrow-up" style="font-size:13px"></i>
    </button>
    @include('partials.cookie-banner')


    <script>
        setTimeout(() => document.querySelectorAll('[id^=flash-]').forEach(el => el.remove()), 4000);

        window.addEventListener('scroll', () => {
            const btn = document.getElementById('back-to-top');
            if (btn) btn.style.display = window.scrollY > 300 ? 'flex' : 'none';
        });

        function toggleSidebar() {
            const s = document.getElementById('shop-sidebar');
            const ov = document.getElementById('sidebar-overlay');
            const mh = document.getElementById('sidebar-mobile-header');
            const open = s.classList.toggle('mobile-open');
            ov.style.display = open ? 'block' : 'none';
            if (mh) mh.style.display = open ? 'flex' : 'none';
            document.body.style.overflow = open ? 'hidden' : '';
        }

        function liveSearch() {
            return {
                query: '',
                results: [],
                open: false,
                async search() {
                    if (this.query.length < 2) {
                        this.results = [];
                        this.open = false;
                        return;
                    }
                    const r = await fetch('/search?q=' + encodeURIComponent(this.query));
                    this.results = await r.json();
                    this.open = this.results.length > 0;
                },
                goToShop() {
                    if (this.query) window.location.href = '/shop?q=' + encodeURIComponent(this.query);
                    this.open = false;
                }
            };
        }

        function addToCart(productId, qty = 1) {
            if (window.fbTrack) window.fbTrack('AddToCart', {
                content_ids: [productId],
                content_type: 'product',
                num_items: qty
            });
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    qty
                })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    // Update both desktop and mobile cart counts
                    document.querySelectorAll('#cart-count, .mobile-bottom-nav .cart-btn .badge').forEach(el => {
                        el.textContent = data.count;
                        el.style.display = 'flex';
                    });
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Error', 'error');
                }
            });
        }

        function showToast(msg, type = 'success') {
            const el = document.createElement('div');
            el.style.cssText =
                `position:fixed;bottom:72px;left:50%;transform:translateX(-50%);z-index:9999;background:${type === 'success' ? 'var(--teal)' : '#dc2626'};color:#fff;padding:10px 20px;border-radius:25px;font-size:13px;font-weight:600;white-space:nowrap;box-shadow:0 4px 20px rgba(0,0,0,.2)`;
            el.textContent = msg;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2500);
        }

        // PWA — lets customers "Add to Home Screen"; sw.js deliberately never
        // caches page content, only static assets, so cart/prices/stock stay live.
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').catch(() => {});
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
