<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserOtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(
        private UserOtpService $userOtpService,
    ) {}

    public function create(): View
    {
        abort_unless(config('perks.registration_enabled'), 404);

        return view('auth.register');
    }

    public function sendOtp(Request $request): JsonResponse
    {
        abort_unless(config('perks.registration_enabled'), 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
        ]);

        $phone = '+995'.$request->phone;

        if (User::where('phone', $phone)->whereNotNull('phone')->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'An account with this phone number already exists. Please login instead.',
            ], 422);
        }

        $key = 'send-otp:'.$phone;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'success' => false,
                'message' => "Too many attempts. Please try again in {$seconds} seconds.",
            ], 429);
        }

        RateLimiter::hit($key, 60 * 15);

        try {
            $this->userOtpService->issue($phone, 'register');
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'SMS delivery failed.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent successfully.',
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        abort_unless(config('perks.registration_enabled'), 404);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $phone = $request->phone;
        $userOtp = $this->userOtpService->verify($phone, 'register', $request->otp);

        if (User::where('phone', $phone)->orWhere('email', $request->email)->exists()) {
            throw ValidationException::withMessages([
                'email' => ['An account with this email or phone already exists.'],
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make(uniqid('', true)),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->userOtpService->cleanup($phone, 'register', $userOtp->id);

        return redirect()->intended(route('home'));
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('register');
    }
}
