<?php

namespace App\Contracts\Sms;

use App\DataTransferObjects\Sms\OtpChallenge;

interface OtpProvider
{
    public function send(string $phoneNumber): OtpChallenge;

    public function verify(string $hash, string $phoneNumber, string $code): bool;
}
