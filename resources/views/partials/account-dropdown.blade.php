<div x-show="userMenu" @click.away="userMenu=false" x-cloak class="dropdown-panel">
    @auth
        <div class="dropdown-header">
            <p>{{ auth()->user()->name }}</p>
            <p>{{ auth()->user()->phone ?? auth()->user()->email }}</p>
        </div>
        @if(auth()->user()->isManager())
            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                <i class="fas fa-tachometer-alt"></i> Admin Panel
            </a>
        @endif
        @foreach([['account.orders', 'fa-box', 'My Orders'], ['account.profile', 'fa-user-cog', 'Account'], ['account.wishlist', 'fa-heart', 'Wishlist']] as [$rt, $ic, $lb])
            <a href="{{ route($rt) }}" class="dropdown-item">
                <i class="fas {{ $ic }}"></i> {{ $lb }}
            </a>
        @endforeach
        <div class="dropdown-divider">
            <form action="{{ route('auth.logout') }}" method="POST">@csrf
                <button type="submit" class="dropdown-item danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    @else
        @foreach([['auth.login', 'fa-sign-in-alt', 'Login'], ['auth.register', 'fa-user-plus', 'Register'], ['auth.otp', 'fa-mobile-alt', 'Login with OTP']] as [$rt, $ic, $lb])
            <a href="{{ route($rt) }}" class="dropdown-item">
                <i class="fas {{ $ic }}"></i> {{ $lb }}
            </a>
        @endforeach
    @endauth
</div>
