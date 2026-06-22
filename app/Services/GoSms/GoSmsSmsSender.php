<?php

namespace App\Services\GoSms;

use App\Contracts\Sms\SmsSender;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\Log;

class GoSmsSmsSender implements SmsSender
{
    public function __construct(
        private GoSmsClient $client,
    ) {}

    public function send(string $phoneNumber, string $message, bool $urgent = false): bool
    {
        $sender = config('services.gosms.sender_name');

        if (! $sender) {
            throw new GoSmsException('GoSMS sender name is not configured.');
        }

        $response = $this->client->post('/api/sendsms', [
            'from' => $sender,
            'to' => PhoneNumber::toGoSms($phoneNumber),
            'text' => $message,
            'urgent' => $urgent,
        ]);

        Log::info('SMS sent via GoSMS', [
            'phone' => PhoneNumber::toGoSms($phoneNumber),
            'message_id' => $response['messageId'] ?? null,
            'balance' => $response['balance'] ?? null,
        ]);

        return true;
    }
}
