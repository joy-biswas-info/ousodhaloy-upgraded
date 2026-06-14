@extends('layouts.shop')
@section('title', 'Forgot Password')
@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-lg border overflow-hidden" x-data="forgotForm()">

                <div class="bg-gradient-to-br from-teal-900 to-teal-700 p-8 text-center text-white">
                    <div
                        class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-teal-700 font-black text-2xl mx-auto mb-3">
                        🔑</div>
                    <h1 class="text-2xl font-black">Reset Password</h1>
                    <p class="text-white/70 text-sm mt-1">Verify your phone to set a new password</p>
                </div>

                <div class="p-7 space-y-5">

                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                            @foreach ($errors->all() as $e)
                                <p>{{ $e }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Step 1: Phone --}}
                    <div x-show="step === 1">
                        <p class="text-sm text-gray-600 mb-4">Enter the phone number linked to your account and we'll send
                            you a 6-digit OTP.</p>
                        <div>
                            <label class="form-label">Phone Number</label>
                            <input type="tel" x-model="phone" class="form-input text-center text-lg tracking-widest"
                                placeholder="01XXXXXXXXX">
                        </div>
                        <p x-show="message" :class="success ? 'text-green-600' : 'text-red-600'"
                            class="text-xs text-center mt-2 font-semibold" x-text="message"></p>
                        <button @click="sendOtp()" :disabled="loading" class="btn-primary w-full py-3 mt-4">
                            <span x-show="!loading"><i class="fas fa-mobile-alt mr-2"></i>Send OTP</span>
                            <span x-show="loading"><i class="fas fa-spinner fa-spin mr-2"></i>Sending...</span>
                        </button>
                    </div>

                    {{-- Step 2: OTP + New Password --}}
                    <div x-show="step === 2">
                        <div class="text-center mb-4 text-sm text-gray-600">
                            OTP sent to <span class="font-bold text-gray-800" x-text="phone"></span>
                            <button @click="step = 1; message = ''"
                                class="text-teal-700 hover:underline ml-1">Change</button>
                        </div>

                        <form method="POST" action="{{ route('auth.forgot.reset') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="phone" :value="phone">

                            <div>
                                <label class="form-label">6-digit OTP</label>
                                <input type="text" name="code" maxlength="6"
                                    class="form-input text-center text-2xl tracking-[0.5em] font-mono" placeholder="––––––"
                                    autofocus>
                            </div>

                            <div>
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-input"
                                    placeholder="Minimum 6 characters">
                            </div>

                            <div>
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-input"
                                    placeholder="Repeat your new password">
                            </div>

                            <button type="submit" class="btn-primary w-full py-3">
                                <i class="fas fa-lock mr-2"></i>Reset Password
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <button @click="sendOtp()" :disabled="resendCountdown > 0"
                                class="text-xs text-teal-700 hover:underline disabled:text-gray-400">
                                <span x-show="resendCountdown === 0">Resend OTP</span>
                                <span x-show="resendCountdown > 0">Resend in <span x-text="resendCountdown"></span>s</span>
                            </button>
                        </div>
                    </div>

                    <div class="text-center text-sm text-gray-600 border-t pt-4">
                        Remembered it?
                        <a href="{{ route('auth.login') }}" class="text-teal-700 font-semibold hover:underline">Sign In</a>
                    </div>
                </div>
            </div>

            <p class="text-center text-xs text-gray-400 mt-4">
                <a href="{{ route('home') }}" class="hover:text-teal-700">← Back to Store</a>
            </p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function forgotForm() {
            return {
                step: 1,
                phone: '',
                loading: false,
                message: '',
                success: false,
                resendCountdown: 0,

                async sendOtp() {
                    if (!this.phone) {
                        this.message = 'Enter your phone number';
                        this.success = false;
                        return;
                    }
                    this.loading = true;
                    this.message = '';
                    const res = await fetch('{{ route('auth.forgot.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({
                            phone: this.phone
                        })
                    });
                    const data = await res.json();
                    this.loading = false;
                    this.success = data.success;
                    this.message = data.message;
                    if (data.success) {
                        this.step = 2;
                        this.startResendTimer();
                    }
                },

                startResendTimer() {
                    this.resendCountdown = 120;
                    const t = setInterval(() => {
                        if (this.resendCountdown <= 0) {
                            clearInterval(t);
                            return;
                        }
                        this.resendCountdown--;
                    }, 1000);
                }
            };
        }
    </script>
@endpush
