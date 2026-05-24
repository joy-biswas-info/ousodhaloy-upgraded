<div x-show="userMenu" @click.away="userMenu=false" x-cloak
    style="position:absolute;right:0;top:calc(100% + 6px);background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.18);border:1px solid #e5e7eb;width:200px;z-index:600;overflow:hidden;padding:4px 0">
    @auth
        <div style="padding:10px 14px 8px;font-size:12px;color:#6b7280;border-bottom:1px solid #f3f4f6">
            <p style="font-weight:700;color:#1f2937;margin:0">{{ auth()->user()->name }}</p>
            <p style="margin:0">{{ auth()->user()->phone ?? auth()->user()->email }}</p>
        </div>
        @if(auth()->user()->isManager())
            <a href="{{ route('admin.dashboard') }}"
                style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                <i class="fas fa-tachometer-alt" style="color:var(--teal);width:14px"></i> Admin Panel
            </a>
        @endif
        @foreach([['account.orders', 'fa-box', 'My Orders'], ['account.profile', 'fa-user-cog', 'Account'], ['account.wishlist', 'fa-heart', 'Wishlist']] as [$rt, $ic, $lb])
            <a href="{{ route($rt) }}"
                style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                <i class="fas {{ $ic }}" style="color:var(--teal);width:14px"></i> {{ $lb }}
            </a>
        @endforeach
        <div style="border-top:1px solid #f3f4f6;margin-top:2px">
            <form action="{{ route('auth.logout') }}" method="POST">@csrf
                <button type="submit"
                    style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#dc2626;background:none;border:none;cursor:pointer;font-family:inherit">
                    <i class="fas fa-sign-out-alt" style="width:14px"></i> Logout
                </button>
            </form>
        </div>
    @else
        @foreach([['auth.login', 'fa-sign-in-alt', 'Login'], ['auth.register', 'fa-user-plus', 'Register'], ['auth.otp', 'fa-mobile-alt', 'Login with OTP']] as [$rt, $ic, $lb])
            <a href="{{ route($rt) }}"
                style="display:flex;align-items:center;gap:10px;padding:10px 14px;font-size:13px;color:#374151;text-decoration:none"
                @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
                <i class="fas {{ $ic }}" style="color:var(--teal);width:14px"></i> {{ $lb }}
            </a>
        @endforeach
    @endauth
</div>