<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\{User, Otp};
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [$loginField => $request->login, 'password' => $request->password];

        if (Auth::attempt($credentials, $request->remember)) {
            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->with('error', 'Your account has been suspended.');
            }
            $request->session()->regenerate();
            return redirect()->intended(route('home'))->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors(['login' => 'Invalid credentials.'])->withInput($request->except('password'));
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'phone' => 'required|regex:/^01[3-9]\d{8}$/|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'phone.regex' => 'Please enter a valid Bangladeshi phone number (01XXXXXXXXX)',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'referral_code' => strtoupper(Str::random(8)),
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Welcome to Ousodhaloy! 🎉');
    }

    // ── OTP Login (phone-based) ──────────────────────────────

    public function otpForm()
    {
        return view('auth.otp');
    }

    public function sendOtp(Request $request, SmsService $sms)
    {
        $request->validate(['phone' => 'required|regex:/^01[3-9]\d{8}$/']);

        // Rate limiting
        $recentOtp = Otp::where('phone', $request->phone)
            ->where('created_at', '>', now()->subMinutes(2))
            ->exists();
        if ($recentOtp) {
            return response()->json(['success' => false, 'message' => 'Please wait 2 minutes before requesting another OTP.'], 429);
        }

        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        Otp::create([
            'phone' => $request->phone,
            'code' => Hash::make($code),
            'purpose' => 'login',
            'expires_at' => now()->addMinutes((int) config('app.otp_expiry', 5)),
        ]);

        $sms->otp($request->phone, $code);

        return response()->json(['success' => true, 'message' => 'OTP sent to your phone.']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'code' => 'required|digits:6',
        ]);

        $otp = Otp::where('phone', $request->phone)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp || !Hash::check($request->code, $otp->code)) {
            return back()->withErrors(['code' => 'Invalid or expired OTP.']);
        }

        $otp->update(['is_used' => true]);

        // Login or register
        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            ['name' => 'User-' . substr($request->phone, -4), 'role' => 'customer', 'referral_code' => strtoupper(Str::random(8))]
        );

        $user->update(['phone_verified_at' => now()]);
        Auth::login($user, true);

        return redirect()->route('home')->with('success', 'Logged in successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out successfully.');
    }
}
