<?php

namespace App\Services\Sms;

use App\Contracts\Sms\SmsSender;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Log;

class LogSmsSender implements SmsSender
{
    public function send(string $phoneNumber, string $message, bool $urgent = false): bool
    {
        Log::info('SMS (logged only — GoSMS disabled)', [
            'phone' => PhoneNumber::toGoSms($phoneNumber),
            'urgent' => $urgent,
            'message' => $message,
        ]);

        return true;
    }
}
