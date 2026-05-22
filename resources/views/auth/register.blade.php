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
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                    @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
                </div>
                @endif
                <form method="POST" action="{{ route('auth.register.post') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Your full name" required>
                    </div>
                    <div>
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="01XXXXXXXXX" required>
                        <p class="text-xs text-gray-400 mt-1">Valid Bangladeshi mobile number</p>
                    </div>
                    <div>
                        <label class="form-label">Email Address (optional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="email@example.com">
                    </div>
                    <div>
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" placeholder="Min 6 characters" required>
                    </div>
                    <div>
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Repeat password" required>
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
