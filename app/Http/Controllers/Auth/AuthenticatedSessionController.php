<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserOtp;
use App\Services\SmsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private SmsService $smsService
    ) {}

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Send OTP for login
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
        ], [
            'phone.required' => 'ტელეფონის ნომერი აუცილებელია.',
            'phone.regex' => 'გთხოვთ, შეიყვანოთ სწორი ქართული ტელეფონის ნომერი (9 ციფრი).',
        ]);

        $phone = '+995' . $request->phone;

        // Check if user exists
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'ამ ტელეფონის ნომერზე ანგარიში არ მოიძებნა. გთხოვთ, ჯერ დარეგისტრირდეთ.',
            ], 422);
        }

        // Rate limiting
        $key = 'send-otp:' . $phone;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "ძალიან ბევრი მცდელობა. გთხოვთ, სცადოთ ხელახლა {$seconds} წამში.",
            ], 429);
        }

        RateLimiter::hit($key, 60 * 15); // 15 minutes

        // Generate OTP
        $otpCode = $this->smsService->generateVerificationCode();

        // Delete old OTPs for this phone
        UserOtp::where('phone', $phone)
            ->where('type', 'login')
            ->whereNull('verified_at')
            ->delete();

        // Create new OTP
        $userOtp = UserOtp::create([
            'phone' => $phone,
            'otp_code' => $otpCode,
            'type' => 'login',
            'expires_at' => now()->addMinutes(10),
        ]);

        // Send SMS
        $this->smsService->sendVerificationCode($phone, $otpCode);

        return response()->json([
            'success' => true,
            'message' => 'დადასტურების კოდი წარმატებით გაიგზავნა.',
        ]);
    }

    /**
     * Verify OTP and login
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $phone = $request->phone;

        // Find valid OTP
        $userOtp = UserOtp::forPhone($phone, 'login')
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

        // Find user and login
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            throw ValidationException::withMessages([
                'phone' => ['User not found.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        // Clean up old OTPs
        UserOtp::where('phone', $phone)
            ->where('type', 'login')
            ->where('id', '!=', $userOtp->id)
            ->delete();

        return redirect()->intended(route('home'));
    }

    /**
     * Handle an incoming authentication request (legacy - kept for compatibility).
     */
    public function store(Request $request): RedirectResponse
    {
        // This method is kept for backward compatibility but should not be used
        // with the new OTP flow
        return redirect()->route('login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
