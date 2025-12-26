<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use App\Services\SmsService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private SmsService $smsService
    ) {}

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Send OTP for registration
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
        ]);

        $phone = '+995' . $request->phone;

        // Check if phone already exists (for registration, phone must be unique)
        if (User::where('phone', $phone)->whereNotNull('phone')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'An account with this phone number already exists. Please login instead.',
            ], 422);
        }

        // Rate limiting
        $key = 'send-otp:' . $phone;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Too many attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($key, 60 * 15); // 15 minutes

        // Generate OTP
        $otpCode = $this->smsService->generateVerificationCode();

        // Delete old OTPs for this phone
        UserOtp::where('phone', $phone)
            ->where('type', 'register')
            ->whereNull('verified_at')
            ->delete();

        // Create new OTP
        $userOtp = UserOtp::create([
            'phone' => $phone,
            'otp_code' => $otpCode,
            'type' => 'register',
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send SMS
        $this->smsService->sendVerificationCode($phone, $otpCode);

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully.',
        ]);
    }

    /**
     * Verify OTP and create user account
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $phone = $request->phone;

        // Find valid OTP
        $userOtp = UserOtp::forPhone($phone, 'register')
            ->valid()
            ->latest()
            ->first();

        if (!$userOtp) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid or expired verification code.'],
            ]);
        }

        // Check OTP code
        if ($userOtp->otp_code !== $request->otp) {
            $userOtp->incrementAttempts();

            if ($userOtp->attempts >= 5) {
                $userOtp->delete();
                throw ValidationException::withMessages([
                    'otp' => ['Too many failed attempts. Please request a new code.'],
                ]);
            }

            throw ValidationException::withMessages([
                'otp' => ['Invalid verification code.'],
            ]);
        }

        // Mark OTP as verified
        $userOtp->markAsVerified();

        // Check if user already exists (race condition check)
        if (User::where('phone', $phone)->orWhere('email', $request->email)->exists()) {
            throw ValidationException::withMessages([
                'email' => ['An account with this email or phone already exists.'],
        ]);
        }

        // Create user (no password needed for OTP-based auth)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make(uniqid('', true)), // Random password since we use OTP
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Clean up old OTPs
        UserOtp::where('phone', $phone)
            ->where('type', 'register')
            ->where('id', '!=', $userOtp->id)
            ->delete();

        return redirect()->intended(route('home'));
    }

    /**
     * Handle an incoming registration request (legacy - kept for compatibility).
     */
    public function store(Request $request): RedirectResponse
    {
        // This method is kept for backward compatibility but should not be used
        // with the new OTP flow
        return redirect()->route('register');
    }
}
