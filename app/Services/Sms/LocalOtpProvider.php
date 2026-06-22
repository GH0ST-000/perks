<?php

namespace App\Services\Sms;

use App\Contracts\Sms\OtpProvider;
use App\DataTransferObjects\Sms\OtpChallenge;
use Illuminate\Support\Facades\Log;

class LocalOtpProvider implements OtpProvider
{
    public function send(string $phoneNumber): OtpChallenge
    {
        $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        Log::info('Local OTP generated (GoSMS disabled)', [
            'phone' => $phoneNumber,
            'code' => $code,
        ]);

        return new OtpChallenge(
            hash: null,
            code: $code,
        );
    }

    public function verify(string $hash, string $phoneNumber, string $code): bool
    {
        return false;
    }
}
