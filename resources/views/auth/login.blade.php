{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.shop')
@section('title', 'Login')
@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
                <div class="bg-gradient-to-br from-teal-900 to-teal-700 p-8 text-center text-white">
                    <div
                        class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-teal-700 font-black text-2xl mx-auto mb-3">
                        ও</div>
                    <h1 class="text-2xl font-black">Welcome Back</h1>
                    <p class="text-white/70 text-sm mt-1">Sign in to your Ousodhaloy account</p>
                </div>
                <div class="p-7 space-y-4">
                    <form method="POST" action="{{ route('auth.login.post') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="form-label" for="login-input">Email or Phone</label>
                            <input type="text" name="login" id="login-input" value="{{ old('login') }}"
                                class="form-input @error('login') border-red-400 @enderror"
                                placeholder="email@example.com or 01XXXXXXXXX" required autofocus>
                            @error('login') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label" for="password-input">Password</label>
                            <input type="password" name="password" id="password-input"
                                class="form-input @error('password') border-red-400 @enderror"
                                placeholder="Your password" required>
                            @error('password') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <label class="flex items-center gap-2 text-gray-600 cursor-pointer">
                                <input type="checkbox" name="remember" class="accent-teal-600"> Remember me
                            </label>
                            <a href="{{ route('auth.forgot') }}" class="text-teal-700 hover:underline font-semibold">Forgot
                                password?</a>
                        </div>
                        <button type="submit" class="btn-primary w-full py-3">Sign In</button>
                    </form>
                    <div class="text-center">
                        <a href="{{ route('auth.otp') }}" class="text-xs text-teal-700 hover:underline font-semibold">
                            <i class="fas fa-mobile-alt mr-1"></i>Login with OTP (Phone)
                        </a>
                    </div>
                    <div class="text-center text-sm text-gray-600">
                        Don't have an account?
                        <a href="{{ route('auth.register') }}" class="text-teal-700 font-semibold hover:underline">Sign
                            Up</a>
                    </div>
                </div>
            </div>
            <p class="text-center text-xs text-gray-400 mt-4">
                <a href="{{ route('home') }}" class="hover:text-teal-700">← Back to Store</a>
            </p>
        </div>
    </div>
@endsection
