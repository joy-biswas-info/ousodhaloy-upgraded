<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'Ousodhaloy')); ?> – Bangladesh\'s Trusted Online Pharmacy</title>
    <meta name="description"
        content="<?php echo $__env->yieldContent('meta_description', 'Buy genuine medicine, healthcare and wellness products online. Fast delivery across Bangladesh.'); ?>">

    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    },
                },
            },
        }
    </script>

    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    
    <style>
        :root {
            --teal: #0e7673;
            --teal-dark: #0a5250;
            --teal-light: #13a09c;
            --teal-bg: #e6f4f4;
            --orange: #f97316;
            --red: #dc2626;
        }

        body {
            font-family: 'Inter', 'Hind Siliguri', sans-serif;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        [x-cloak] {
            display: none !important;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        /* Product cards */
        .product-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            transition: box-shadow 0.2s, border-color 0.2s, transform 0.15s;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
            border-color: #13a09c;
            transform: translateY(-2px);
        }

        .product-card .card-img {
            width: 100%;
            height: 150px;
            background: #f8fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 12px;
        }

        .product-card .card-body {
            padding: 12px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-card .card-brand {
            font-size: 11px;
            color: #9ca3af;
            margin-bottom: 2px;
        }

        .product-card .card-name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            line-height: 1.35;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-card .card-generic {
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .product-card .card-price {
            display: flex;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 8px;
        }

        .product-card .price-now {
            font-size: 15px;
            font-weight: 800;
            color: var(--teal);
        }

        .product-card .price-was {
            font-size: 11px;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .product-card .card-add-btn {
            margin-top: auto;
            width: 100%;
            background: var(--teal-bg);
            color: var(--teal);
            border: 1px solid var(--teal);
            border-radius: 7px;
            padding: 7px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }

        .product-card .card-add-btn:hover {
            background: var(--teal);
            color: #fff;
        }

        .product-card .card-add-btn:disabled {
            background: #f3f4f6;
            color: #9ca3af;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        /* Badges */
        .badge-discount {
            position: absolute;
            top: 8px;
            left: 8px;
            background: var(--red);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-flash {
            position: absolute;
            top: 8px;
            right: 8px;
            background: var(--orange);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .badge-express {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(14, 118, 115, 0.8);
            color: #fff;
            font-size: 10px;
            font-weight: 600;
            padding: 3px 8px;
            text-align: center;
        }

        .badge-rx {
            position: absolute;
            top: 26px;
            left: 8px;
            background: #2563eb;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
        }

        /* Status badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-confirmed {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-processing {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-ready_to_ship {
            background: #cffafe;
            color: #155e75;
        }

        .status-shipped {
            background: #ede9fe;
            color: #5b21b6;
        }

        .status-out_for_delivery {
            background: #fed7aa;
            color: #9a3412;
        }

        .status-delivered {
            background: #d1fae5;
            color: #065f46;
        }

        .status-cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-refunded,
        .status-on_hold {
            background: #f3f4f6;
            color: #374151;
        }

        .status-returned {
            background: #fecaca;
            color: #7f1d1d;
        }

        /* Form styles */
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .form-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            font-family: inherit;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            color: #1f2937;
            background: #fff;
        }

        .form-input:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px var(--teal-bg);
        }

        .form-select {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            outline: none;
            background: #fff;
            cursor: pointer;
            font-family: inherit;
        }

        .form-select:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px var(--teal-bg);
        }

        .form-error {
            font-size: 11px;
            color: var(--red);
            margin-top: 4px;
        }

        /* Buttons */
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: var(--teal);
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-primary:hover {
            background: var(--teal-dark);
            color: #fff;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: var(--teal-bg);
            color: var(--teal);
            border: 1px solid var(--teal);
            border-radius: 9px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: var(--teal);
            color: #fff;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #fff;
            color: #374151;
            border: 1px solid #d1d5db;
            border-radius: 9px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-outline:hover {
            border-color: #9ca3af;
            background: #f9fafb;
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s;
            font-family: inherit;
            text-decoration: none;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-sm {
            padding: 6px 12px !important;
            font-size: 12px !important;
        }

        /* Products grid */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        @media(min-width:640px) {
            .products-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(min-width:1024px) {
            .products-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media(min-width:1280px) {
            .products-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        /* Reset strikethrough that can bleed from Tailwind prose/line-through utilities */
        label,
        label *,
        .payment-method-label,
        .payment-method-label * {
            text-decoration: none !important;
        }

        /* Prose */
        .prose h2 {
            font-size: 1.15rem;
            font-weight: 700;
            margin-top: 1em;
            margin-bottom: 0.4em;
            color: #111827;
        }

        .prose h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-top: 0.8em;
            color: #1f2937;
        }

        .prose p {
            margin-bottom: 0.6em;
            color: #374151;
            line-height: 1.7;
            font-size: 14px;
        }

        .prose ul,
        .prose ol {
            padding-left: 1.25em;
            margin-bottom: 0.6em;
        }

        .prose li {
            margin-bottom: 0.25em;
            color: #374151;
            font-size: 14px;
        }

        .prose strong {
            font-weight: 700;
        }

        .prose table {
            width: 100%;
            border-collapse: collapse;
            margin: 1em 0;
            font-size: 13px;
        }

        .prose td,
        .prose th {
            border: 1px solid #e5e7eb;
            padding: 8px 12px;
        }

        .prose th {
            background: #f9fafb;
            font-weight: 600;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php echo $__env->make('partials.meta-pixel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</head>

<body class="bg-gray-50 font-inter">

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
        <div id="flash-success"
            class="fixed top-4 right-4 z-[9999] bg-teal-700 text-white px-5 py-3 rounded-xl shadow-xl flex items-center gap-2 text-sm font-semibold animate-slide-in">
            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            <button onclick="this.parentElement.remove()" class="ml-2 opacity-70 hover:opacity-100">&times;</button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
        <div id="flash-error"
            class="fixed top-4 right-4 z-[9999] bg-red-600 text-white px-5 py-3 rounded-xl shadow-xl flex items-center gap-2 text-sm font-semibold">
            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

            <button onclick="this.parentElement.remove()" class="ml-2">&times;</button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('info')): ?>
        <div id="flash-info"
            class="fixed top-4 right-4 z-[9999] bg-blue-600 text-white px-5 py-3 rounded-xl shadow-xl flex items-center gap-2 text-sm font-semibold">
            <i class="fas fa-info-circle"></i> <?php echo e(session('info')); ?>

            <button onclick="this.parentElement.remove()" class="ml-2">&times;</button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-teal-900 text-white text-xs py-1.5 hidden sm:block">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <span><i class="fas fa-flag mr-1"></i>🇧🇩 Free delivery above
                ৳<?php echo e(\App\Models\Setting::get('free_delivery_min', 500)); ?> | Call:
                <?php echo e(\App\Models\Setting::get('site_phone', '09610016778')); ?></span>
            <div class="flex gap-5">
                <a href="<?php echo e(route('track')); ?>" class="hover:text-teal-200 transition-colors"><i
                        class="fas fa-search-location mr-1"></i>Track Order</a>
                <a href="<?php echo e(route('checkout.index')); ?>?rx=1" class="hover:text-teal-200 transition-colors"><i
                        class="fas fa-file-medical mr-1"></i>Upload Prescription</a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?> <a href="<?php echo e(route('auth.login')); ?>" class="hover:text-teal-200 transition-colors"><i c
                   lass="fas fa-sign-in-alt mr-1"></i>Login</a> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    
    <header class="bg-teal-700 sticky top-0 z-50 shadow-md" x-data="{ userMenu: false }">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center gap-4">

            
            <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 flex-shrink-0 text-white">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(\App\Models\Setting::get('site_logo')): ?>
                    <img src="<?php echo e(asset('storage/' . \App\Models\Setting::get('site_logo'))); ?>" class="h-9 w-auto"
                        alt="<?php echo e(\App\Models\Setting::get('site_name', 'Ousodhaloy')); ?>">
                <?php else: ?>
                    <div
                        class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-teal-700 font-black text-xl">
                        ও</div>
                    <span
                        class="font-bold text-xl tracking-tight hidden sm:block"><?php echo e(\App\Models\Setting::get('site_name', 'Ousodhaloy')); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>

            
            <div class="flex-1 max-w-2xl" x-data="liveSearch()" @click.away="open = false">
                <div class="flex items-center bg-white rounded-lg overflow-hidden shadow-sm">
                    <i class="fas fa-search text-gray-400 pl-4 pr-2 text-sm"></i>
                    <input type="text" x-model="query" @input.debounce.300ms="search()"
                        @focus="open = results.length > 0" @keydown.enter="goToShop()" @keydown.escape="open = false"
                        placeholder="ওষুধের নাম, জেনেরিক, ব্র্যান্ড লিখুন..."
                        class="flex-1 px-2 py-2.5 text-sm outline-none text-gray-800">
                    <button @click="goToShop()"
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2.5 text-sm font-semibold transition-colors flex-shrink-0">
                        <span class="hidden sm:inline">Search</span><i class="fas fa-arrow-right sm:hidden"></i>
                    </button>
                </div>

                
                <div x-show="open && results.length" x-cloak
                    class="absolute mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 max-h-96 overflow-y-auto"
                    style="width: min(560px, calc(100vw - 2rem))">
                    <template x-for="p in results" :key="p . id">
                        <a :href="`/shop/product/${p . slug}`"
                            class="flex items-center gap-3 px-4 py-3 hover:bg-teal-50 border-b last:border-0 transition-colors">
                            <div
                                class="w-11 h-11 bg-gray-50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                <img x-show="p.thumbnail_url" :src="p . thumbnail_url"
                                    class="w-full h-full object-contain">
                                <span x-show="!p.thumbnail_url" class="text-2xl">💊</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="p.name"></p>
                                <p class="text-xs text-gray-500"
                                    x-text="(p.generic_name || '') + (p.brand ? ' · ' + p.brand : '')"></p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-sm font-bold text-teal-700">৳<span x-text="p.price"></span></p>
                                <template x-if="p.discount > 0">
                                    <p class="text-xs text-red-500 font-semibold">-<span x-text="p.discount"></span>%
                                    </p>
                                </template>
                            </div>
                        </a>
                    </template>
                    <div class="px-4 py-2 bg-gray-50 text-center">
                        <button @click="goToShop()" class="text-xs text-teal-700 font-semibold hover:underline">
                            View all results for "<span x-text="query"></span>" →
                        </button>
                    </div>
                </div>
            </div>

            
            <div class="flex items-center gap-2 flex-shrink-0">
                
                <a href="<?php echo e(route('checkout.index')); ?>"
                    class="hidden md:flex items-center gap-1.5 bg-white/15 hover:bg-white/25 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-colors">
                    <i class="fas fa-file-prescription"></i><span class="hidden lg:inline">Upload Rx</span>
                </a>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('account.wishlist')); ?>"
                        class="hidden md:flex items-center gap-1 bg-white/15 hover:bg-white/25 text-white px-3 py-2 rounded-lg text-xs transition-colors">
                        <i class="fas fa-heart"></i>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <a href="<?php echo e(route('cart.index')); ?>"
                    class="flex items-center gap-1.5 bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-colors relative">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="hidden sm:inline">Cart</span>
                    <span id="cart-count"
                        class="absolute -top-1.5 -right-1.5 bg-white text-orange-600 text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center <?php echo e(\App\Http\Controllers\Shop\CartController::getCount() === 0 ? 'hidden' : ''); ?>">
                        <?php echo e(\App\Http\Controllers\Shop\CartController::getCount()); ?>

                    </span>
                </a>

                
                <div class="relative">
                    <button @click="userMenu = !userMenu"
                        class="flex items-center gap-1.5 bg-white/15 hover:bg-white/25 text-white px-3 py-2 rounded-lg text-xs font-semibold transition-colors">
                        <i class="fas fa-user"></i>
                        <span
                            class="hidden sm:inline"><?php echo e(auth()->user()?->name ? Str::words(auth()->user()->name, 1, '') : 'Login'); ?></span>
                        <i class="fas fa-chevron-down text-[10px]"></i>
                    </button>
                    <div x-show="userMenu" @click.away="userMenu = false" x-cloak
                        class="absolute right-0 top-full mt-1 bg-white rounded-xl shadow-2xl border w-52 z-50 py-1 overflow-hidden">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                            <div class="px-4 py-2.5 text-xs text-gray-500 border-b">
                                <p class="font-bold text-gray-800"><?php echo e(auth()->user()->name); ?></p>
                                <p><?php echo e(auth()->user()->phone ?? auth()->user()->email); ?></p>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->isManager()): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>"
                                    class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50">
                                    <i class="fas fa-tachometer-alt text-teal-600 w-4"></i> Admin Panel
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <a href="<?php echo e(route('account.orders')); ?>"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50">
                                <i class="fas fa-box text-teal-600 w-4"></i> My Orders
                            </a>
                            <a href="<?php echo e(route('account.profile')); ?>"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50">
                                <i class="fas fa-user-cog text-teal-600 w-4"></i> Account
                            </a>
                            <a href="<?php echo e(route('account.wishlist')); ?>"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50">
                                <i class="fas fa-heart text-teal-600 w-4"></i> Wishlist
                            </a>
                            <div class="border-t mt-1">
                                <form action="<?php echo e(route('auth.logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt w-4"></i> Logout
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo e(route('auth.login')); ?>"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50">
                                <i class="fas fa-sign-in-alt text-teal-600 w-4"></i> Login
                            </a>
                            <a href="<?php echo e(route('auth.register')); ?>"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50">
                                <i class="fas fa-user-plus text-teal-600 w-4"></i> Register
                            </a>
                            <a href="<?php echo e(route('auth.otp')); ?>"
                                class="flex items-center gap-2.5 px-4 py-2.5 text-sm hover:bg-gray-50">
                                <i class="fas fa-mobile-alt text-teal-600 w-4"></i> Login with OTP
                            </a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    
    <nav class="bg-white border-b shadow-sm sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center gap-1 overflow-x-auto scrollbar-hide h-11">
                <a href="<?php echo e(route('shop.index')); ?>"
                    class="flex-shrink-0 text-xs font-medium text-gray-600 hover:text-teal-700 hover:bg-teal-50 px-3 py-2 rounded-lg transition-colors whitespace-nowrap <?php echo e(request()->routeIs('shop.index') && !request()->has('category') ? 'text-teal-700 bg-teal-50' : ''); ?>">
                    🏠 All
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Category::active()->take(12)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('shop.index', ['category' => $cat->slug])); ?>"
                        class="flex-shrink-0 text-xs font-medium px-3 py-2 rounded-lg transition-colors whitespace-nowrap <?php echo e(request('category') === $cat->slug ? 'text-teal-700 bg-teal-50 font-semibold' : 'text-gray-600 hover:text-teal-700 hover:bg-teal-50'); ?>">
                        <?php echo e($cat->icon); ?> <?php echo e($cat->name); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </nav>

    
    <main class="min-h-screen">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <footer class="bg-gray-900 text-gray-400 pt-12 pb-6 mt-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-9 h-9 bg-teal-600 rounded-lg flex items-center justify-center text-white font-black text-lg">
                            ও</div>
                        <span
                            class="text-white font-bold text-lg"><?php echo e(\App\Models\Setting::get('site_name', 'Ousodhaloy')); ?></span>
                    </div>
                    <p class="text-sm leading-relaxed mb-4">বাংলাদেশের বিশ্বস্ত অনলাইন ফার্মেসি। আসল ওষুধ, দ্রুত
                        ডেলিভারি।</p>
                    <div class="flex gap-3 mb-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i
                                class="fab fa-facebook text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i
                                class="fab fa-instagram text-xl"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors"><i
                                class="fab fa-youtube text-xl"></i></a>
                    </div>
                    <div class="flex gap-2">
                        <a href="#"
                            class="bg-gray-800 hover:bg-gray-700 text-xs px-3 py-2 rounded-lg transition-colors flex items-center gap-1.5">
                            <i class="fab fa-apple"></i> App Store
                        </a>
                        <a href="#"
                            class="bg-gray-800 hover:bg-gray-700 text-xs px-3 py-2 rounded-lg transition-colors flex items-center gap-1.5">
                            <i class="fab fa-google-play"></i> Play Store
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3 text-sm">Quick Links</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="<?php echo e(route('home')); ?>" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="<?php echo e(route('shop.index')); ?>" class="hover:text-white transition-colors">All
                                Products</a></li>
                        <li><a href="<?php echo e(route('shop.index', ['category' => 'medicine'])); ?>"
                                class="hover:text-white transition-colors">Medicine</a></li>
                        <li><a href="<?php echo e(route('track')); ?>" class="hover:text-white transition-colors">Track Order</a>
                        </li>
                        <li><a href="<?php echo e(route('auth.login')); ?>" class="hover:text-white transition-colors">My
                                Account</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3 text-sm">Company</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms of Use</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-3 text-sm">Contact</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center gap-2"><i class="fas fa-phone text-teal-500"></i>
                            <?php echo e(\App\Models\Setting::get('site_phone', '09610016778')); ?></li>
                        <li class="flex items-center gap-2"><i class="fas fa-envelope text-teal-500"></i> <?php echo e(\App\Models\Setting::get('site_email','info@ousodhaloy.com')); ?></li>
                        <li class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-teal-500"></i>
                            <?php echo e(\App\Models\Setting::get('site_address', 'Dhaka, Bangladesh')); ?></li>
                        <li class="flex items-center gap-2"><i class="fas fa-clock text-teal-500"></i> 24/7 Support</li>
                    </ul>
                </div>
            </div>
            <div
                class="border-t border-gray-800 pt-6 flex flex-col md:flex-row justify-between items-center gap-3 text-xs">
                <p>© <?php echo e(date('Y')); ?> <?php echo e(\App\Models\Setting::get('site_name', 'Ousodhaloy')); ?> Ltd. All rights reserved.
                </p>
                <div class="flex gap-4">
                    <span><i class="fas fa-certificate text-teal-500 mr-1"></i>DGDA Licensed</span>
                    <span><i class="fas fa-lock text-teal-500 mr-1"></i>SSL Secured</span>
                    <span><i class="fas fa-check-circle text-teal-500 mr-1"></i>100% Genuine</span>
                </div>
            </div>
        </div>
    </footer>

    
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed bottom-6 right-6 bg-teal-700 text-white w-10 h-10 rounded-full shadow-lg hidden items-center justify-center hover:bg-teal-800 transition-colors z-40">
        <i class="fas fa-arrow-up text-sm"></i>
    </button>

    <script>
        // Flash auto-dismiss
        setTimeout(() => document.querySelectorAll('[id^=flash-]').forEach(el => el.remove()), 4000);

        // Back to top
        window.addEventListener('scroll', () => {
            const btn = document.getElementById('back-to-top');
            if (btn) btn.classList.toggle('hidden', window.scrollY < 300);
            if (btn) btn.classList.toggle('flex', window.scrollY >= 300);
        });

        // Live search Alpine component
        function liveSearch() {
            return {
                query: '',
                results: [],
                open: false,
                async search() {
                    if (this.query.length < 2) { this.results = []; this.open = false; return; }
                    const res = await fetch(`/search?q=${encodeURIComponent(this.query)}`);
                    this.results = await res.json();
                    this.open = this.results.length > 0;
                },
                goToShop() {
                    if (this.query) window.location.href = `/shop?q=${encodeURIComponent(this.query)}`;
                    this.open = false;
                }
            };
        }

        // Add to cart via AJAX
        function addToCart(productId, qty = 1) {
            if (window.fbTrack) {
                window.fbTrack('AddToCart', { content_ids: [productId], content_type: 'product', num_items: qty });
            }
            fetch('/cart/add', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ product_id: productId, qty })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        const count = document.getElementById('cart-count');
                        if (count) { count.textContent = data.count; count.classList.remove('hidden'); }
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.message || 'Error', 'error');
                    }
                });
        }

        function showToast(msg, type = 'success') {
            const el = document.createElement('div');
            el.className = `fixed bottom-4 right-4 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-semibold flex items-center gap-2 ${type === 'success' ? 'bg-teal-700' : 'bg-red-600'}`;
            el.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>${msg}`;
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 3000);
        }
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/layouts/shop.blade.php ENDPATH**/ ?>