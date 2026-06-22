<?php

namespace App\Services;

use App\Contracts\Sms\OtpProvider;
use App\Contracts\Sms\SmsSender;
use App\DataTransferObjects\Sms\OtpChallenge;
use App\Services\GoSms\GoSmsException;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Log;

class SmsService
{
    public function __construct(
        private SmsSender $smsSender,
        private OtpProvider $otpProvider,
    ) {}

    public function generateVerificationCode(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    public function dispatchOtp(string $phoneNumber): OtpChallenge
    {
        return $this->otpProvider->send($phoneNumber);
    }

    public function verifyExternalOtp(string $hash, string $phoneNumber, string $code): bool
    {
        return $this->otpProvider->verify($hash, $phoneNumber, $code);
    }

    public function sendVerificationCode(string $phoneNumber, string $verificationCode): bool
    {
        $message = "თქვენი Perks დადასტურების კოდია: {$verificationCode}. ვადა: 10 წუთი.";

        return $this->sendMessage($phoneNumber, $message, urgent: true);
    }

    public function sendDiscountNotification(string $phoneNumber, float $discount, string $companyName): bool
    {
        $message = "გილოცავთ! მიიღეთ {$discount}% ფასდაკლება {$companyName}-დან. აჩვენეთ ეს SMS გადახდისას.";

        return $this->sendMessage($phoneNumber, $message);
    }

    public function sendMessage(string $phoneNumber, string $message, bool $urgent = false): bool
    {
        if (! $this->validatePhoneNumber($phoneNumber)) {
            Log::warning('SMS skipped — invalid phone number', ['phone' => $phoneNumber]);

            return false;
        }

        try {
            return $this->smsSender->send($phoneNumber, $message, $urgent);
        } catch (GoSmsException $e) {
            Log::error('SMS delivery failed', [
                'phone' => PhoneNumber::toGoSms($phoneNumber),
                'error' => $e->getMessage(),
                'error_code' => $e->errorCode,
            ]);

            return false;
        }
    }

    public function validatePhoneNumber(string $phoneNumber): bool
    {
        $cleaned = preg_replace('/[\s\-\(\)\+]/', '', $phoneNumber);

        return preg_match('/^[0-9]{10,15}$/', (string) $cleaned) === 1;
    }

    public function isEnabled(): bool
    {
        return (bool) config('services.gosms.enabled');
    }
}
