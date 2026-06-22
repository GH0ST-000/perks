<?php

namespace App\Services;

use App\Models\UserOtp;
use App\Services\GoSms\GoSmsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class UserOtpService
{
    public function __construct(
        private SmsService $smsService,
    ) {}

    public function issue(string $phone, string $type, int $expiresInMinutes = 10): UserOtp
    {
        UserOtp::query()
            ->where('phone', $phone)
            ->where('type', $type)
            ->whereNull('verified_at')
            ->delete();

        try {
            $challenge = $this->smsService->dispatchOtp($phone);
        } catch (GoSmsException $e) {
            Log::error('OTP dispatch failed', [
                'phone' => $phone,
                'type' => $type,
                'error' => $e->getMessage(),
                'error_code' => $e->errorCode,
            ]);

            throw ValidationException::withMessages([
                'phone' => ['SMS-ის გაგზავნა ვერ მოხერხდა. გთხოვთ, სცადოთ ხელახლა.'],
            ]);
        }

        if (! $challenge->usesExternalVerification() && $challenge->code) {
            $this->smsService->sendVerificationCode($phone, $challenge->code);
        }

        return UserOtp::create([
            'phone' => $phone,
            'otp_code' => $challenge->code,
            'provider_otp_hash' => $challenge->hash,
            'type' => $type,
            'expires_at' => now()->addMinutes($expiresInMinutes),
        ]);
    }

    public function verify(string $phone, string $type, string $otp): UserOtp
    {
        $userOtp = UserOtp::forPhone($phone, $type)
            ->valid()
            ->latest()
            ->first();

        if (! $userOtp) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid or expired verification code.'],
            ]);
        }

        $isValid = $userOtp->provider_otp_hash
            ? $this->verifyExternal($userOtp, $phone, $otp)
            : $this->verifyLocal($userOtp, $otp);

        if (! $isValid) {
            throw ValidationException::withMessages([
                'otp' => ['Invalid verification code.'],
            ]);
        }

        $userOtp->markAsVerified();

        return $userOtp;
    }

    public function cleanup(string $phone, string $type, int $exceptId): void
    {
        UserOtp::query()
            ->where('phone', $phone)
            ->where('type', $type)
            ->where('id', '!=', $exceptId)
            ->delete();
    }

    private function verifyExternal(UserOtp $userOtp, string $phone, string $otp): bool
    {
        try {
            $verified = $this->smsService->verifyExternalOtp($userOtp->provider_otp_hash, $phone, $otp);
        } catch (GoSmsException $e) {
            Log::error('OTP verification failed', [
                'phone' => $phone,
                'error' => $e->getMessage(),
                'error_code' => $e->errorCode,
            ]);

            $verified = false;
        }

        if ($verified) {
            return true;
        }

        $userOtp->incrementAttempts();
        $userOtp->refresh();

        if ($userOtp->attempts >= 5) {
            $userOtp->delete();

            throw ValidationException::withMessages([
                'otp' => ['Too many failed attempts. Please request a new code.'],
            ]);
        }

        return false;
    }

    private function verifyLocal(UserOtp $userOtp, string $otp): bool
    {
        if ($userOtp->otp_code === $otp) {
            return true;
        }

        $userOtp->incrementAttempts();
        $userOtp->refresh();

        if ($userOtp->attempts >= 5) {
            $userOtp->delete();

            throw ValidationException::withMessages([
                'otp' => ['Too many failed attempts. Please request a new code.'],
            ]);
        }

        return false;
    }
}
