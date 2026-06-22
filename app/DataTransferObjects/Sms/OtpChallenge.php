<?php

namespace App\DataTransferObjects\Sms;

readonly class OtpChallenge
{
    public function __construct(
        public ?string $hash,
        public ?string $code,
    ) {}

    public function usesExternalVerification(): bool
    {
        return $this->hash !== null;
    }
}
