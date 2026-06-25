<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\User;
use App\Services\PartnerAccountService;
use App\Services\UserOtpService;
use App\Support\PhoneNumber;
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
        private UserOtpService $userOtpService,
    ) {}

    public function create(): View
    {
        return view('auth.login');
    }

    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9]{9}$/'],
        ], [
            'phone.required' => 'ტელეფონის ნომერი აუცილებელია.',
            'phone.regex' => 'გთხოვთ, შეიყვანოთ სწორი ქართული ტელეფონის ნომერი (9 ციფრი).',
        ]);

        $phone = PhoneNumber::normalize($request->phone);

        $user = User::where('phone', $phone)->first();

        if (! $user) {
            $partner = Partner::query()
                ->get()
                ->first(fn (Partner $partner): bool => app(PartnerAccountService::class)->normalizePartnerPhone($partner->phone) === $phone);

            if ($partner) {
                $user = app(PartnerAccountService::class)->syncLoginUser($partner);
            }
        }

        if (! $user) {
            $message = config('perks.registration_enabled')
                ? 'ამ ტელეფონის ნომერზე ანგარიში არ მოიძებნა. გთხოვთ, დარეგისტრირდეთ.'
                : 'ამ ტელეფონის ნომერზე ანგარიში არ მოიძებნა. დაუკავშირდით ადმინისტრატორს.';

            return response()->json([
                'success' => false,
                'message' => $message,
                'register_url' => config('perks.registration_enabled') ? route('register') : null,
            ], 422);
        }

        $key = 'send-otp:'.$phone;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return response()->json([
                'success' => false,
                'message' => "ძალიან ბევრი მცდელობა. გთხოვთ, სცადოთ ხელახლა {$seconds} წამში.",
            ], 429);
        }

        RateLimiter::hit($key, 60 * 15);

        try {
            $this->userOtpService->issue($phone, 'login');
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first() ?? 'SMS-ის გაგზავნა ვერ მოხერხდა.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'დადასტურების კოდი წარმატებით გაიგზავნა.',
        ]);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $phone = $request->phone;
        $userOtp = $this->userOtpService->verify($phone, 'login', $request->otp);

        $user = User::where('phone', $phone)->first();
        if (! $user) {
            throw ValidationException::withMessages([
                'phone' => ['User not found.'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $this->userOtpService->cleanup($phone, 'login', $userOtp->id);

        return redirect()->intended(
            $user->isPartner() ? route('partner.dashboard') : route('dashboard')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        return redirect()->route('login');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
