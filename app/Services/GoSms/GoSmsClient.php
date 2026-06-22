<?php

namespace App\Services\GoSms;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GoSmsClient
{
    public function post(string $path, array $payload = []): array
    {
        $apiKey = config('services.gosms.api_key');

        if (! $apiKey) {
            throw new GoSmsException('GoSMS API key is not configured.');
        }

        $response = Http::baseUrl(rtrim((string) config('services.gosms.base_url'), '/'))
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('services.gosms.timeout', 15))
            ->post($this->path($path), array_merge($payload, [
                'api_key' => $apiKey,
            ]));

        return $this->decode($response);
    }

    private function path(string $path): string
    {
        return '/'.ltrim($path, '/');
    }

    private function decode(Response $response): array
    {
        $body = $response->json() ?? [];

        if (! $response->successful() || ($body['success'] ?? false) !== true) {
            throw new GoSmsException(
                $body['message'] ?? $body['error'] ?? 'GoSMS request failed.',
                (int) ($body['errorCode'] ?? 0),
                $response->status()
            );
        }

        return $body;
    }
}
