<?php

namespace App\Services\Sms;

use App\Contracts\Sms\OtpProvider;
use App\Contracts\Sms\SmsSender;
use App\DataTransferObjects\Sms\OtpChallenge;

class SmsOtpProvider implements OtpProvider
{
    public function __construct(
        private SmsSender $smsSender,
    ) {}

    public function send(string $phoneNumber): OtpChallenge
    {
        $code = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $message = "თქვენი Perks დადასტურების კოდია: {$code}. ვადა: 10 წუთი.";
        $this->smsSender->send($phoneNumber, $message, urgent: true);

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
