<?php

namespace App\Contracts\Sms;

interface SmsSender
{
    public function send(string $phoneNumber, string $message, bool $urgent = false): bool;
}
