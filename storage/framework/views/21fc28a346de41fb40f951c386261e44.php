<?php $__env->startSection('title', 'Login with OTP'); ?>
<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-lg border overflow-hidden" x-data="otpForm()">
            <div class="bg-gradient-to-br from-teal-900 to-teal-700 p-7 text-center text-white">
                <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-teal-700 font-black text-2xl mx-auto mb-3">📱</div>
                <h1 class="text-2xl font-black">OTP Login</h1>
                <p class="text-white/70 text-sm mt-1">Enter your phone — we'll send a 6-digit OTP</p>
            </div>
            <div class="p-7 space-y-5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <p><?php echo e($e); ?></p> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div x-show="!otpSent">
                    <div>
                        <label class="form-label">Phone Number</label>
                        <input type="tel" x-model="phone" class="form-input text-center text-lg tracking-widest" placeholder="01XXXXXXXXX">
                    </div>
                    <button @click="sendOtp()" :disabled="loading" class="btn-primary w-full py-3 mt-4">
                        <span x-show="!loading"><i class="fas fa-mobile-alt mr-2"></i>Send OTP</span>
                        <span x-show="loading"><i class="fas fa-spinner fa-spin mr-2"></i>Sending...</span>
                    </button>
                    <p x-show="message" :class="success ? 'text-green-600' : 'text-red-600'" class="text-xs text-center mt-2 font-semibold" x-text="message"></p>
                </div>

                
                <div x-show="otpSent">
                    <div class="text-center mb-4 text-sm text-gray-600">
                        OTP sent to <span class="font-bold text-gray-800" x-text="phone"></span>
                        <button @click="otpSent = false; phone = ''" class="text-teal-700 hover:underline ml-1">Change</button>
                    </div>
                    <form method="POST" action="<?php echo e(route('auth.otp.verify')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="phone" :value="phone">
                        <div>
                            <label class="form-label">Enter 6-digit OTP</label>
                            <input type="text" name="code" maxlength="6" class="form-input text-center text-2xl tracking-[0.5em] font-mono" placeholder="––––––" autofocus>
                        </div>
                        <button type="submit" class="btn-primary w-full py-3 mt-4">Verify & Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <button @click="sendOtp()" :disabled="resendCountdown > 0" class="text-xs text-teal-700 hover:underline disabled:text-gray-400">
                            <span x-show="resendCountdown === 0">Resend OTP</span>
                            <span x-show="resendCountdown > 0">Resend in <span x-text="resendCountdown"></span>s</span>
                        </button>
                    </div>
                </div>

                <div class="text-center text-sm text-gray-600 border-t pt-4">
                    <a href="<?php echo e(route('auth.login')); ?>" class="text-teal-700 font-semibold hover:underline">Login with password instead</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function otpForm() {
    return {
        phone: '', loading: false, otpSent: false, message: '', success: false, resendCountdown: 0,
        async sendOtp() {
            if (!this.phone) { this.message = 'Enter your phone number'; this.success = false; return; }
            this.loading = true; this.message = '';
            const res = await fetch('<?php echo e(route('auth.otp.send')); ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ phone: this.phone })
            });
            const data = await res.json();
            this.loading = false;
            this.success = data.success;
            this.message = data.message;
            if (data.success) {
                this.otpSent = true;
                this.startResendTimer();
            }
        },
        startResendTimer() {
            this.resendCountdown = 120;
            const t = setInterval(() => {
                if (this.resendCountdown <= 0) { clearInterval(t); return; }
                this.resendCountdown--;
            }, 1000);
        }
    };
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.shop', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/joybiswas/Downloads/ousodhaloy-laravel/resources/views/auth/otp.blade.php ENDPATH**/ ?>