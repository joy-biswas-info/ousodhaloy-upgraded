<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ \App\Models\Setting::get('brand_primary', '#0e7673') }}">
    <title>@yield('title', config('app.name', 'Ousodhaloy')) – Bangladesh's Trusted Online Pharmacy</title>
    <meta name="description" content="@yield('meta_description', 'Buy genuine medicine, healthcare and wellness products online. Fast delivery across Bangladesh.')">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    {{-- Tailwind CSS - configure custom teal before script loads --}}
    <script>
        window.tailwind = window.tailwind || {};
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            50: '#f0fafa',
                            100: '#e6f4f4',
                            200: '#c4e8e8',
                            300: '#93d5d5',
                            400: '#5bbebe',
                            500: '#35a5a5',
                            600: '#13a09c',
                            700: '#0e7673',
                            800: '#0a5250',
                            900: '#073f3d',
                        },
                    },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $bp = \App\Models\Setting::get('brand_primary', '#0e7673');
        $bd = \App\Models\Setting::get('brand_dark', '#0a5250');
        $bl = \App\Models\Setting::get('brand_light', '#13a09c');
        $bbg = \App\Models\Setting::get('brand_bg', '#e6f4f4');
        $messengerUrl = \App\Models\Setting::get('messenger_url', '');
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
    </style>
    @stack('styles')
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
            <div style="height:52px;display:flex;align-items:center;gap:6px;padding:0 12px;">

                {{-- Hamburger --}}
                <button onclick="toggleSidebar()" aria-label="Categories"
                    style="background:rgba(255,255,255,.18);border:none;color:#fff;width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0">
                    <i class="fas fa-bars" style="font-size:16px"></i>
                </button>
                {{-- Logo --}}
                {{-- <a href="{{ route('home') }}"
                    style="display:flex;align-items:center;gap:8px;flex-shrink:0;text-decoration:none;color:#fff;">
                    @if (\App\Models\Setting::get('site_logo'))
                        <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}"
                            style="height:60px;width:auto"
                            alt="{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}">
                    @else
                        <div
                            style="width:36px;height:36px;background:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:18px;color:var(--teal);">
                            ও</div>
                        <span style="font-weight:800;font-size:18px;letter-spacing:-.3px;"
                            class="hidden-mobile">{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}</span>
                    @endif
                </a> --}}
                {{-- Logo --}}
                {{-- Logo --}}
                <a href="{{ route('home') }}"
                    style="display:flex;align-items:center;text-decoration:none;color:#fff;flex:1;min-width:0">
                    <div
                        style="width:84px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:16px">
                    ঔষ<span class=" text-red-400">ধা</span>লয়
                    </div>
                </a>

                {{-- Cart --}}
                <a href="{{ route('cart.index') }}"
                    style="position:relative;background:rgba(255,255,255,.18);color:#fff;width:40px;height:40px;border-radius:9px;display:flex;align-items:center;justify-content:center;text-decoration:none;flex-shrink:0">
                    <i class="fas fa-shopping-cart" style="font-size:17px"></i>
                    <span id="cart-count-mobile"
                        style="position:absolute;top:-5px;right:-5px;background:var(--orange);color:#fff;font-size:9px;font-weight:800;min-width:18px;height:18px;border-radius:9px;align-items:center;justify-content:center;padding:0 3px;border:2px solid var(--teal);display:{{ \App\Http\Controllers\Shop\CartController::getCount() === 0 ? 'none' : 'flex' }}">
                        {{ \App\Http\Controllers\Shop\CartController::getCount() }}
                    </span>
                </a>

                {{-- Account --}}
                <div style="position:relative;flex-shrink:0">
                    <button @click="userMenu=!userMenu"
                        style="background:rgba(255,255,255,.18);border:none;color:#fff;width:40px;height:40px;border-radius:9px;display:flex;align-items:center;justify-content:center;cursor:pointer">
                        <i class="fas fa-user" style="font-size:16px"></i>
                    </button>
                    @include('partials.account-dropdown')
                </div>
            </div>

            {{-- ── Row 2: Search ── --}}
            <div style="padding:0 12px 10px" x-data="liveSearch()" @click.away="open=false">
                <div
                    style="position:relative;display:flex;align-items:center;background:#fff;border-radius:10px;overflow:visible">
                    <i class="fas fa-search"
                        style="color:#9ca3af;padding:0 8px 0 12px;font-size:13px;flex-shrink:0"></i>
                    <input type="text" x-model="query" @input.debounce.280ms="search()"
                        @focus="if(results.length) open=true" @keydown.enter="goToShop()" @keydown.escape="open=false"
                        placeholder="Search medicines, brands..."
                        style="flex:1;padding:10px 8px;font-size:14px;outline:none;border:none;background:transparent;font-family:inherit">
                    <button @click="goToShop()"
                        style="background:var(--orange);color:#fff;border:none;padding:10px 16px;font-size:14px;font-weight:700;cursor:pointer;border-radius:0 10px 10px 0;flex-shrink:0">
                        <i class="fas fa-search"></i>
                    </button>
                    @include('partials.search-dropdown')
                </div>
            </div>
        </div>
    </header>

    {{-- ── Desktop HEADER ───────────────────────────────────────────────────────── --}}
    <header class="site-header hidden lg:block px-4 my-auto">
        <div class="header-inner" x-data="{ userMenu: false }">
            {{-- Logo --}}
            <a href="{{ route('home') }}"
                style="display:flex;align-items:center;gap:8px;text-decoration:none;color:#fff;flex:1;min-width:0">
                <div
                    style="width:110px;height:42px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:24px">
                    ঔষ<span class=" text-red-400">ধা</span>লয়
                </div>
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

                {{-- Search dropdown --}}
                <div x-show="open && results.length" x-cloak
                    style="position:absolute;top:calc(100% + 6px);left:0;right:0;background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.15);border:1px solid #e5e7eb;z-index:500;max-height:380px;overflow-y:auto;">
                    <template x-for="p in results" :key="p.id">
                        <a :href="'/shop/product/' + p.slug"
                            style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-bottom:1px solid #f3f4f6;text-decoration:none;transition:background .12s"
                            @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                            <div
                                style="width:40px;height:40px;background:#f8fafb;border-radius:8px;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                                <img x-show="p.thumbnail_url" :src="p.thumbnail_url"
                                    style="width:100%;height:100%;object-fit:contain;">
                                <span x-show="!p.thumbnail_url" style="font-size:20px;">💊</span>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-size:13px;font-weight:600;color:#1f2937;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                                    x-text="p.name"></p>
                                <p style="font-size:11px;color:#6b7280"
                                    x-text="(p.generic_name||'')+(p.brand?' · '+p.brand:'')"></p>
                            </div>
                            <div style="text-align:right;flex-shrink:0;">
                                <p style="font-size:13px;font-weight:800;color:var(--teal)">৳<span
                                        x-text="p.price"></span></p>
                                <p x-show="p.discount>0" style="font-size:11px;color:#dc2626;font-weight:600">-<span
                                        x-text="p.discount"></span>%</p>
                            </div>
                        </a>
                    </template>
                    <div style="padding:10px;text-align:center;background:#f9fafb;">
                        <button @click="goToShop()"
                            style="font-size:12px;color:var(--teal);font-weight:600;background:none;border:none;cursor:pointer;">
                            View all for "<span x-text="query"></span>" →
                        </button>
                    </div>
                </div>
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
                    <div x-show="userMenu" @click.away="userMenu=false" x-cloak
                        style="position:absolute;right:0;top:calc(100%+4px);background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.15);border:1px solid #e5e7eb;width:200px;z-index:500;overflow:hidden;padding:4px 0">
                        @auth
                            <div
                                style="padding:10px 14px 8px;font-size:12px;color:#6b7280;border-bottom:1px solid #f3f4f6">
                                <p style="font-weight:700;color:#1f2937">{{ auth()->user()->name }}</p>
                                <p>{{ auth()->user()->phone ?? auth()->user()->email }}</p>
                            </div>
                            @if (auth()->user()->isManager())
                                <a href="{{ route('admin.dashboard') }}"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                                    @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                                    <i class="fas fa-tachometer-alt" style="color:var(--teal);width:14px"></i> Admin Panel
                                </a>
                            @endif
                            @foreach ([['account.orders', 'fa-box', 'My Orders'], ['account.profile', 'fa-user-cog', 'Account'], ['account.wishlist', 'fa-heart', 'Wishlist']] as [$rt, $ic, $lb])
                                <a href="{{ route($rt) }}"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                                    @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                                    <i class="fas {{ $ic }}" style="color:var(--teal);width:14px"></i>
                                    {{ $lb }}
                                </a>
                            @endforeach
                            <div style="border-top:1px solid #f3f4f6;margin-top:2px">
                                <form action="{{ route('auth.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#dc2626;background:none;border:none;cursor:pointer;font-family:inherit">
                                        <i class="fas fa-sign-out-alt" style="width:14px"></i> Logout
                                    </button>
                                </form>
                            </div>
                        @else
                            @foreach ([['auth.login', 'fa-sign-in-alt', 'Login'], ['auth.register', 'fa-user-plus', 'Register'], ['auth.otp', 'fa-mobile-alt', 'Login with OTP']] as [$rt, $ic, $lb])
                                <a href="{{ route($rt) }}"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                                    @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                                    <i class="fas {{ $ic }}" style="color:var(--teal);width:14px"></i>
                                    {{ $lb }}
                                </a>
                            @endforeach
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- ── BODY: sidebar + full-width content ────────────────────────── --}}
    <div style="display:flex;min-height:calc(100vh - 60px)">

        {{-- LEFT CATEGORY SIDEBAR ── --}}
        <aside class="shop-sidebar" id="shop-sidebar" style="background:#fff;border-right:1px solid #e5e7eb;">

            {{-- Mobile close button --}}
            <div id="sidebar-mobile-header"
                style="display:none;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #f3f4f6;background-color:var(--teal)">
                {{-- Logo --}}
                {{-- <a href="{{ route('home') }}"
                    style="display:flex;align-items:center;gap:8px;flex-shrink:0;text-decoration:none;color:#fff;">
                    @if (\App\Models\Setting::get('site_logo'))
                        <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}"
                            style="height:60px;width:auto"
                            alt="{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}">
                    @else
                        <div
                            style="width:36px;height:36px;background:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:18px;color:var(--teal);">
                            ও</div>
                        <span style="font-weight:800;font-size:18px;letter-spacing:-.3px;"
                            class="hidden-mobile">{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}</span>
                    @endif
                </a> --}}
                {{-- Logo --}}
                <a href="{{ route('home') }}"
                    style="display:flex;align-items:center;text-decoration:none;color:#fff;flex:1;min-width:0">
                    <div
                        style="width:84px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:16px">
                    ঔষ<span class=" text-red-500">ধা</span>লয়
                    </div>
                </a>
                <button class="text-white" onclick="toggleSidebar()"
                    style="background:none;border:none;font-size:22px;color:#9ca3af;cursor:pointer;line-height:1">&times;</button>
            </div>

            <!-- <div style="padding:14px 14px 6px">
                <p style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:1px">Shop
                    by Category</p>
            </div> -->

            <nav class="px-1">
                <a href="{{ route('shop.index') }}"
                    class="cat-nav-link {{ request()->routeIs('shop.index') && !request()->has('category') ? 'active' : '' }}">
                    <span class="cat-nav-icon">🏠</span>
                    <span style="flex:1">All Products</span>
                </a>
                @foreach (\App\Models\Category::active()->get() as $cat)
                    <a href="{{ route('shop.index', ['category' => $cat->slug]) }}"
                        class="border-b border-gray-100 cat-nav-link {{ request('category') === $cat->slug ? 'active' : '' }}"
                        onclick="if(window.innerWidth<1024)toggleSidebar()">
                        <span class="cat-nav-icon">{{ $cat->icon }}</span>
                        <span
                            style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $cat->name }}</span>
                        {{-- @if ($cat->product_count > 0)
                            <span
                                style="font-size:10px;color:#9ca3af;flex-shrink:0;margin-left:4px">{{ $cat->product_count }}</span>
                        @endif --}}
                    </a>
                @endforeach
            </nav>

            {{-- Rx CTA --}}
            <div style="margin:14px 10px;padding:14px;background:var(--teal-bg);border-radius:12px;text-align:center">
                <div style="font-size:26px;margin-bottom:6px">💊</div>
                <p style="font-size:12px;font-weight:700;color:#1f2937;margin-bottom:3px">Need a prescription?</p>
                <p style="font-size:11px;color:#6b7280;margin-bottom:10px;line-height:1.4">Upload your Rx — we'll pick
                    the right medicines</p>
                <a href="{{ route('checkout.index') }}"
                    style="display:block;background:var(--teal);color:#fff;font-size:12px;font-weight:700;padding:9px;border-radius:8px;text-decoration:none">
                    Upload Prescription
                </a>
            </div>
        </aside>

        {{-- MAIN CONTENT — full remaining width --}}
        <main style="flex:1;overflow-x:auto">
            <section class="container sm:px-1 md:px-3 lg:px-4 full-width" style="min-height: 90vh">
                @yield('content')
            </section>
            {{-- ── FOOTER ────────────────────────────────────────────────────────── --}}

            <footer style="background:#111827;color:#9ca3af;padding:0 0px 40px 0px;margin-top:0">
                <div style="background:#fff;border-bottom:1px solid #e5e7eb" class=" w-full">
                    <div style="margin:0 auto;padding:10px 16px">
                        <div
                            style="display:flex;align-items:center;justify-content:space-around;flex-wrap:wrap;gap:8px">
                            @foreach ([['fas fa-truck', 'Fast Delivery', '24-48hrs'], ['fas fa-headset', '24/7 Support', 'Always here'], ['fas fa-shield-alt', 'Secure Pay', 'bKash · Card']] as [$icon, $text, $sub])
                                <div style="display:flex;align-items:center;gap:8px;padding:4px 0">
                                    <i class="{{ $icon }}" style="color:var(--teal);font-size:10px"></i>
                                    <div>
                                        <p style="font-size:10px;font-weight:700;color:#1f2937;margin:0">
                                            {{ $text }}</p>
                                        <p style="font-size:10px;color:#9ca3af;margin:0">{{ $sub }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div style="max-width:1400px;margin:0 auto;padding:16px">
                    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:24px 16px;margin-bottom:32px">
                        <div style="grid-column:1/-1">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                                <div
                                    style="width:64px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:16px">
                    ঔষ<span class=" text-red-400">ধা</span>লয়
                                </div>
                            </div>
                            <p style="font-size:12px;line-height:1.7;margin-bottom:8px">Trusted Health and Wellness
                                Shop in Bangladesh</p>
                        </div>
                        <div>
                            <p style="color:#fff;font-weight:600;font-size:13px;margin-bottom:10px">Quick Links</p>
                            @foreach ([['home', 'Home'], ['shop.index', 'All Products'], ['track', 'Track Order'], ['auth.login', 'My Account'],['legal.privacy', 'Privacy Policy'],['legal.terms',"Terms"],['legal.returns','Return Policy']] as [$rt, $lb])
                                <a href="{{ route($rt) }}"
                                    style="display:block;font-size:12px;color:#9ca3af;text-decoration:none;margin-bottom:6px"
                                    @mouseenter="$el.style.color='#fff'"
                                    @mouseleave="$el.style.color='#9ca3af'">{{ $lb }}</a>
                            @endforeach
                        </div>
                        <div>
                            <p style="color:#fff;font-weight:600;font-size:13px;margin-bottom:10px">Contact</p>
                            <p style="font-size:12px;margin-bottom:6px"><i class="fas fa-phone"
                                    style="color:var(--teal-light);margin-right:6px"></i>{{ \App\Models\Setting::get('site_phone', '09610016778') }}
                            </p>
                            <p style="font-size:12px;margin-bottom:6px"><i class="fas fa-envelope"
                                    style="color:var(--teal-light);margin-right:6px"></i>{{ \App\Models\Setting::get('site_email', 'info@ousodhaloy.com') }}
                            </p>
                            <p style="font-size:12px"><i class="fas fa-map-marker-alt"
                                    style="color:var(--teal-light);margin-right:6px"></i>{{ \App\Models\Setting::get('site_address', 'Dhaka, Bangladesh') }}
                            </p>
                        </div>
                    </div>
                    <div
                        style="border-top:1px solid #1f2937;padding-top:16px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:8px;font-size:11px">
                        <p>© {{ date('Y') }} {{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }} Ltd. All
                            rights
                            reserved.
                        </p>
                        <div style="display:flex;gap:14px">
                            <span><i class="fas fa-certificate"
                                    style="color:var(--teal-light);margin-right:4px"></i>DGDA
                                Licensed</span>
                            <span><i class="fas fa-lock" style="color:var(--teal-light);margin-right:4px"></i>SSL
                                Secured</span>
                        </div>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    {{-- Sidebar overlay (mobile) --}}
    <div id="sidebar-overlay" onclick="toggleSidebar()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:199"></div>

    {{-- ── MESSENGER FAB ────────────────────────────────────────────────── --}}
    @if ($messengerUrl)
        <a href="{{ $messengerUrl }}" target="_blank" rel="noopener" class="messenger-fab"
            aria-label="Chat on Messenger">
            <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="34" cy="34" r="34" fill="#0084FF" />
                <path
                    d="M24 10C16.268 10 10 15.82 10 23c0 3.876 1.748 7.354 4.558 9.826V37l4.242-2.334A15.16 15.16 0 0024 35c7.732 0 14-5.82 14-12S31.732 10 24 10z"
                    fill="white" />
                <path d="M13 26l6-6.4 4.5 4.5L30 20l-6.2 6.6-4.3-4.5L13 26z" fill="#0084FF" />
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
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        style="display:none;position:fixed;bottom:80px;right:16px;background:var(--teal);color:#fff;width:40px;height:40px;border-radius:50%;border:none;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,.2);z-index:90;align-items:center;justify-content:center;transition:background .15s">
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
    </script>
    @stack('scripts')
</body>

</html>
