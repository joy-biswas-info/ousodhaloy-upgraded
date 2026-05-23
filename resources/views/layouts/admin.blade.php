<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') – {{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }} Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/admin.css', 'resources/js/admin.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --teal: #0e7673;
            --teal-dark: #0a5250;
            --teal-bg: #e6f4f4;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

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
            color: #dc2626;
            margin-top: 4px;
        }

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

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .admin-table th {
            background: #f9fafb;
            padding: 10px 14px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }

        .admin-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        .admin-table tr:hover td {
            background: #fafafa;
        }

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

        .editor-btn {
            padding: 4px 8px;
            border-radius: 5px;
            border: none;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            background: transparent;
            cursor: pointer;
            transition: background 0.15s;
        }

        .editor-btn:hover {
            background: #e5e7eb;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-gray-100 font-inter" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    <aside class="fixed left-0 top-0 h-full w-64 bg-gray-900 z-50 flex flex-col transition-transform duration-300"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        {{-- Brand --}}
        <div class="p-5 border-b border-gray-800 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white font-black">ও
                </div>
                <div>
                    <p class="text-white font-bold text-sm">{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}
                    </p>
                    <p class="text-gray-500 text-xs">Admin Panel</p>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 p-3 overflow-y-auto space-y-0.5">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'icon' => 'tachometer-alt', 'label' => 'Dashboard'],
                    ['route' => 'admin.orders.index', 'icon' => 'box', 'label' => 'Orders', 'badge' => \App\Models\Order::where('status', 'pending')->count()],
                    ['route' => 'admin.orders.create', 'icon' => 'plus-circle', 'label' => 'New Order'],
                    ['route' => 'admin.products.index', 'icon' => 'pills', 'label' => 'Products'],
                    ['route' => 'admin.products.bulk', 'icon' => 'file-import', 'label' => 'Bulk Import'],
                    ['route' => 'admin.categories.index', 'icon' => 'th-large', 'label' => 'Categories'],
                    ['route' => 'admin.brands.index', 'icon' => 'trademark', 'label' => 'Brands'],
                    ['route' => 'admin.users.index', 'icon' => 'users', 'label' => 'Customers'],
                    ['route' => 'admin.prescriptions', 'icon' => 'file-medical', 'label' => 'Prescriptions', 'badge' => \App\Models\Prescription::where('status', 'pending')->count()],
                    ['route' => 'admin.reviews', 'icon' => 'star', 'label' => 'Reviews', 'badge' => \App\Models\ProductReview::where('is_approved', false)->count()],
                    ['route' => 'admin.customization.index', 'icon' => 'paint-brush', 'label' => 'Customization'],
                    ['route' => 'admin.media.index', 'icon' => 'images', 'label' => 'Media Library'],
                    ['route' => 'admin.settings.index', 'icon' => 'cog', 'label' => 'Settings'],
                    ['route' => 'admin.settings.promos', 'icon' => 'tag', 'label' => 'Promo Codes'],
                    ['route' => 'admin.settings.banners', 'icon' => 'images', 'label' => 'Banners'],
                    ['route' => 'admin.sms-logs', 'icon' => 'sms', 'label' => 'SMS Logs'],
                ];
            @endphp
            @foreach($navItems as $item)
                <a href="{{ route($item['route']) }}"
                    class="flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-colors group
                        {{ request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*') ? 'bg-teal-700 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-{{ $item['icon'] }} w-4 text-center"></i>
                        {{ $item['label'] }}
                    </div>
                    @if(isset($item['badge']) && $item['badge'] > 0)
                        <span
                            class="bg-red-500 text-white text-[10px] font-bold rounded-full px-1.5 py-0.5 min-w-[18px] text-center">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach

            <div class="pt-3 mt-3 border-t border-gray-800">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-gray-800 hover:text-white transition-colors">
                    <i class="fas fa-globe w-4 text-center"></i> View Store
                </a>
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-400 hover:bg-red-900/30 hover:text-red-400 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                    </button>
                </form>
            </div>
        </nav>

        {{-- User --}}
        <div class="p-4 border-t border-gray-800">
            <p class="text-xs text-gray-500">Logged in as</p>
            <p class="text-sm text-white font-semibold">{{ auth()->user()->name }}</p>
            <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
        </div>
    </aside>

    {{-- Main --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">
        {{-- Top bar --}}
        <header class="bg-white border-b shadow-sm h-14 flex items-center justify-between px-5 sticky top-0 z-40">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-base font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-400 hidden sm:block">@yield('breadcrumb', 'Admin Panel')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
                    class="relative text-gray-500 hover:text-gray-700">
                    <i class="fas fa-bell text-lg"></i>
                    @php $pendingCount = \App\Models\Order::where('status', 'pending')->count(); @endphp
                    @if($pendingCount > 0)
                        <span
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $pendingCount }}</span>
                    @endif
                </a>
                <span class="text-xs text-gray-400">{{ now()->format('D, d M Y') }}</span>
            </div>
        </header>

        {{-- Flash --}}
        @if(session('success'))
            <div
                class="mx-5 mt-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div
                class="mx-5 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-600"></i> {{ session('error') }}
            </div>
        @endif

        <main class="flex-1 p-5">@yield('content')</main>

        <footer class="border-t py-3 px-5 text-center text-xs text-gray-400">
            {{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }} Admin v1.0 &middot; {{ now()->year }}
        </footer>
    </div>

    <script>
        // Admin JS — confirm dialogs, select-all, slug generation
        document.addEventListener('DOMContentLoaded', () => {
            // Select all checkboxes
            const selectAll = document.getElementById('select-all');
            if (selectAll) {
                selectAll.addEventListener('change', () => {
                    document.querySelectorAll('.order-cb, .product-cb').forEach(cb => cb.checked = selectAll.checked);
                });
            }

            // Slug auto-generation
            const nameInput = document.querySelector('[data-slug-source]');
            const slugInput = document.querySelector('[data-slug-target]');
            if (nameInput && slugInput) {
                let slugEdited = !!slugInput.value;
                slugInput.addEventListener('input', () => { slugEdited = true; });
                nameInput.addEventListener('input', () => {
                    if (!slugEdited) slugInput.value = nameInput.value.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-').replace(/^-+|-+$/g, '');
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>