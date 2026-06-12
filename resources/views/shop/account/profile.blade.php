{{-- resources/views/shop/account/profile.blade.php --}}
@extends('layouts.shop')
@section('title', 'My Account')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-black text-gray-800 mb-5">👤 My Account</h1>

        <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
            {{-- Sidebar --}}
            <div class="space-y-2">
                @foreach ([['route' => 'account.profile', 'icon' => 'user-cog', 'label' => 'Profile'], ['route' => 'account.orders', 'icon' => 'box', 'label' => 'My Orders'], ['route' => 'account.wishlist', 'icon' => 'heart', 'label' => 'Wishlist'], ['route' => 'account.addresses', 'icon' => 'map-marker', 'label' => 'Addresses']] as $link)
                    <a href="{{ route($link['route']) }}"
                        class="flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->routeIs($link['route']) ? 'bg-teal-700 text-white' : 'bg-white border text-gray-600 hover:bg-teal-50 hover:text-teal-700' }}">
                        <i class="fas fa-{{ $link['icon'] }} w-4 text-center"></i>
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <form action="{{ route('auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-sm font-medium text-red-600 bg-white border hover:bg-red-50 transition-colors">
                        <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
                    </button>
                </form>
            </div>

            {{-- Profile content --}}
            <div class="sm:col-span-3 space-y-4">
                <div class="bg-white rounded-2xl border p-5">
                    <div class="flex items-center gap-4 mb-5 pb-4 border-b">
                        <div
                            class="w-14 h-14 bg-teal-100 rounded-full flex items-center justify-center text-teal-700 font-black text-2xl">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-800 text-lg">{{ auth()->user()->name }}</h2>
                            <p class="text-sm text-gray-500">Member since {{ auth()->user()->created_at->format('M Y') }}
                            </p>
                            <div class="flex items-center gap-1 mt-1">
                                <i class="fas fa-coins text-yellow-500 text-xs"></i>
                                <span
                                    class="text-xs font-semibold text-gray-600">{{ number_format(auth()->user()->total_loyalty_points) }}
                                    Loyalty Points</span>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4"> @csrf
                        @method('PUT')
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Phone</label>
                                <input type="text" value="{{ auth()->user()->phone }}" class="form-input bg-gray-50"
                                    readonly>
                                @if (auth()->user()->phone_verified_at)
                                    <p class="text-xs text-green-600 mt-1"><i class="fas fa-check-circle mr-1"></i>Verified
                                    </p>
                                @endif
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" class="form-input"
                                    placeholder="Optional">
                            </div>
                        </div>
                        <button type="submit" class="btn-primary btn-sm">Update Profile</button>
                    </form>
                </div>

                {{-- Quick stats --}}
                <div class="grid grid-cols-3 gap-3">
                    @php $user = auth()->user(); @endphp
                    @foreach ([['label' => 'Total Orders', 'value' => $user->orders()->count(), 'icon' => 'box', 'color' => 'teal'], ['label' => 'Delivered', 'value' => $user->orders()->where('status', 'delivered')->count(), 'icon' => 'check', 'color' => 'green'], ['label' => 'Loyalty Points', 'value' => number_format($user->total_loyalty_points), 'icon' => 'star', 'color' => 'yellow']] as $s)
                        <div class="bg-white rounded-xl border p-4 text-center">
                            <i class="fas fa-{{ $s['icon'] }} text-{{ $s['color'] }}-500 text-xl mb-1"></i>
                            <p class="font-black text-gray-800 text-lg">{{ $s['value'] }}</p>
                            <p class="text-xs text-gray-500">{{ $s['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
