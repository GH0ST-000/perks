<?php

namespace App\Services\GoSms;

use App\Contracts\Sms\OtpProvider;
use App\DataTransferObjects\Sms\OtpChallenge;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Log;

class GoSmsOtpProvider implements OtpProvider
{
    public function __construct(
        private GoSmsClient $client,
    ) {}

    public function send(string $phoneNumber): OtpChallenge
    {
        $response = $this->client->post('/api/otp/send', [
            'phone' => PhoneNumber::toGoSms($phoneNumber),
        ]);

        $hash = $response['hash'] ?? null;

        if (! $hash) {
            throw new GoSmsException('GoSMS did not return an OTP hash.');
        }

        Log::info('GoSMS OTP dispatched', [
            'phone' => PhoneNumber::toGoSms($phoneNumber),
        ]);

        return new OtpChallenge(
            hash: $hash,
            code: null,
        );
    }

    public function verify(string $hash, string $phoneNumber, string $code): bool
    {
        $response = $this->client->post('/api/otp/verify', [
            'phone' => PhoneNumber::toGoSms($phoneNumber),
            'hash' => $hash,
            'code' => $code,
        ]);

        return (bool) ($response['verify'] ?? false);
    }
}
