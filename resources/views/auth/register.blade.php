@extends('layouts.shop')
@section('title', 'Create Account')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden">
            <div class="bg-gradient-to-br from-teal-900 to-teal-700 p-7 text-center text-white">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-teal-700 font-black text-2xl mx-auto mb-3">ও</div>
                <h1 class="text-2xl font-black">Create Account</h1>
                <p class="text-white/70 text-sm mt-1">Join Ousodhaloy for faster checkout & order tracking</p>
            </div>
            <div class="p-7 space-y-4">
                <form method="POST" action="{{ route('auth.register.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label" for="register-name-input">Full Name *</label>
                        <input type="text" name="name" id="register-name-input" value="{{ old('name') }}"
                            class="form-input @error('name') border-red-400 @enderror" placeholder="Your full name" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="register-phone-input">Phone Number *</label>
                        <input type="tel" name="phone" id="register-phone-input" value="{{ old('phone') }}"
                            class="form-input @error('phone') border-red-400 @enderror" placeholder="01XXXXXXXXX" required>
                        @error('phone')
                            <p class="form-error">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-400 mt-1">Valid Bangladeshi mobile number</p>
                        @enderror
                    </div>
                    <div>
                        <label class="form-label" for="register-email-input">Email Address (optional)</label>
                        <input type="email" name="email" id="register-email-input" value="{{ old('email') }}"
                            class="form-input @error('email') border-red-400 @enderror" placeholder="email@example.com">
                        @error('email') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="register-password-input">Password *</label>
                        <input type="password" name="password" id="register-password-input"
                            class="form-input @error('password') border-red-400 @enderror" placeholder="Min 6 characters" required>
                        @error('password') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label" for="register-password-confirm-input">Confirm Password *</label>
                        <input type="password" name="password_confirmation" id="register-password-confirm-input" class="form-input" placeholder="Repeat password" required>
                    </div>
                    <button type="submit" class="btn-primary w-full py-3">Create Account</button>
                </form>
                <div class="text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('auth.login') }}" class="text-teal-700 font-semibold hover:underline">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
