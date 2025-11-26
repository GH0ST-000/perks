<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class SmsService
{
    private ?Client $twilioClient = null;
    private bool $twilioEnabled;
    private ?string $twilioPhoneNumber;

    public function __construct()
    {
        $this->twilioEnabled = config('services.twilio.enabled', false);
        $this->twilioPhoneNumber = config('services.twilio.phone_number');

        // Initialize Twilio client if enabled
        if ($this->twilioEnabled) {
            $sid = config('services.twilio.sid');
            $token = config('services.twilio.auth_token');

            if ($sid && $token) {
                try {
                    $this->twilioClient = new Client($sid, $token);
                } catch (\Exception $e) {
                    Log::error('Twilio initialization failed', [
                        'error' => $e->getMessage()
                    ]);
                    $this->twilioEnabled = false;
                }
            } else {
                Log::warning('Twilio credentials not configured');
                $this->twilioEnabled = false;
            }
        }
    }

    /**
     * Generate a 6-digit verification code
     */
    public function generateVerificationCode(): string
    {
        return str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Format phone number for Twilio (add + prefix if not present)
     */
    private function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any spaces or dashes
        $phoneNumber = preg_replace('/[\s\-\(\)]/', '', $phoneNumber);

        // Add + prefix if not present
        if (!str_starts_with($phoneNumber, '+')) {
            $phoneNumber = '+' . $phoneNumber;
        }

        return $phoneNumber;
    }

    /**
     * Send SMS via Twilio or fallback to logging
     */
    private function sendSms(string $phoneNumber, string $message): bool
    {
        $formattedPhone = $this->formatPhoneNumber($phoneNumber);

        if ($this->twilioEnabled && $this->twilioClient) {
            try {
                $this->twilioClient->messages->create(
                    $formattedPhone,
                    [
                        'from' => $this->twilioPhoneNumber,
                        'body' => $message
                    ]
                );

                Log::info('SMS sent via Twilio', [
                    'phone' => $formattedPhone,
                    'message' => $message
                ]);

                return true;
            } catch (TwilioException $e) {
                Log::error('Twilio SMS failed', [
                    'phone' => $formattedPhone,
                    'error' => $e->getMessage(),
                    'code' => $e->getCode()
                ]);

                // Fall back to logging
                $this->logSms($formattedPhone, $message);
                return false;
            }
        } else {
            // Twilio disabled or not configured - just log
            $this->logSms($formattedPhone, $message);
            return true;
        }
    }

    /**
     * Log SMS for testing/development
     */
    private function logSms(string $phoneNumber, string $message): void
    {
        Log::info('SMS (Logged Only - Twilio Disabled)', [
            'phone' => $phoneNumber,
            'message' => $message,
            'twilio_enabled' => $this->twilioEnabled
        ]);
    }

    /**
     * Send SMS verification code to customer
     */
    public function sendVerificationCode(string $phoneNumber, string $verificationCode): bool
    {
        $message = "Your verification code is: {$verificationCode}. Valid for 10 minutes.";
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send discount notification to customer
     */
    public function sendDiscountNotification(string $phoneNumber, float $discount, string $companyName): bool
    {
        $message = "Congratulations! You've received a {$discount}% discount from {$companyName}. Show this SMS at checkout.";
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Validate phone number format
     */
    public function validatePhoneNumber(string $phoneNumber): bool
    {
        // Remove any formatting
        $cleaned = preg_replace('/[\s\-\(\)\+]/', '', $phoneNumber);

        // Check if it's between 10-15 digits
        return preg_match('/^[0-9]{10,15}$/', $cleaned) === 1;
    }

    /**
     * Check if Twilio is enabled and configured
     */
    public function isTwilioEnabled(): bool
    {
        return $this->twilioEnabled && $this->twilioClient !== null;
    }
}
