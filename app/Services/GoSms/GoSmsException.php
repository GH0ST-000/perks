<?php

namespace App\Services\GoSms;

use RuntimeException;

class GoSmsException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $errorCode = 0,
        public readonly int $statusCode = 0,
    ) {
        parent::__construct($message);
    }
}
