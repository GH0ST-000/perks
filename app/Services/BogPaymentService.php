<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class BogPaymentService
{
    private string $baseUrl;
    private string $clientId;
    private string $secretKey;
    private ?string $accessToken = null;
    private ?int $tokenExpiresAt = null;

    public function __construct()
    {
        $this->baseUrl = config('services.bog.base_url');
        $this->clientId = config('services.bog.client_id');
        $this->secretKey = config('services.bog.secret_key');
    }

    /**
     * Get access token for BOG API
     */
    private function getAccessToken(): string
    {
        // Return cached token if still valid
        if ($this->accessToken && $this->tokenExpiresAt && time() < $this->tokenExpiresAt) {
            return $this->accessToken;
        }

        try {
            // BOG uses a separate OAuth2 endpoint with HTTP Basic Auth
            $authUrl = 'https://oauth2.bog.ge/auth/realms/bog/protocol/openid-connect/token';
            
            // Create Basic Auth header
            $basicAuth = base64_encode($this->clientId . ':' . $this->secretKey);
            
            $response = Http::asForm()
                ->withHeaders([
                    'Authorization' => 'Basic ' . $basicAuth,
                ])
                ->post($authUrl, [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->failed()) {
                Log::error('BOG Authentication failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'headers' => $response->headers(),
                ]);
                throw new Exception('Failed to authenticate with BOG API: ' . $response->body());
            }

            $data = $response->json();
            
            if (!$data || !isset($data['access_token'])) {
                Log::error('BOG Authentication response invalid', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'data' => $data,
                ]);
                throw new Exception('Invalid authentication response from BOG API');
            }

            $this->accessToken = $data['access_token'];
            // Set expiry time with 5 minute buffer
            $this->tokenExpiresAt = time() + ($data['expires_in'] ?? 3600) - 300;

            return $this->accessToken;
        } catch (Exception $e) {
            Log::error('BOG Authentication error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Create a payment order
     * 
     * @param array $orderData
     * @return array
     */
    public function createOrder(array $orderData): array
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/payments/v1/ecommerce/orders", [
                    'callback_url' => $orderData['callback_url'],
                    'external_order_id' => $orderData['external_order_id'],
                    'purchase_units' => [
                        'currency' => $orderData['currency'] ?? 'GEL',
                        'total_amount' => $orderData['amount'],
                        'basket' => $orderData['basket'] ?? [],
                    ],
                    'redirect_url' => $orderData['redirect_url'] ?? null,
                    'shop_order_id' => $orderData['shop_order_id'] ?? $orderData['external_order_id'],
                    'locale' => $orderData['locale'] ?? 'ka',
                    'pre_auth' => $orderData['pre_auth'] ?? false,
                    'show_shop_order_id_on_extract' => $orderData['show_shop_order_id_on_extract'] ?? false,
                ]);

            if ($response->failed()) {
                Log::error('BOG Create Order failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_data' => $orderData,
                ]);
                throw new Exception('Failed to create payment order: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('BOG Create Order error', [
                'error' => $e->getMessage(),
                'order_data' => $orderData,
            ]);
            throw $e;
        }
    }

    /**
     * Get order details
     * 
     * @param string $orderId
     * @return array
     */
    public function getOrderDetails(string $orderId): array
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withToken($token)
                ->get("{$this->baseUrl}/payments/v1/ecommerce/orders/{$orderId}");

            if ($response->failed()) {
                Log::error('BOG Get Order Details failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_id' => $orderId,
                ]);
                throw new Exception('Failed to get order details');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('BOG Get Order Details error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            throw $e;
        }
    }

    /**
     * Create order with card binding (for subscriptions)
     * 
     * @param array $orderData
     * @return array
     */
    public function createOrderWithCardBinding(array $orderData): array
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/payments/v1/ecommerce/orders", [
                    'callback_url' => $orderData['callback_url'],
                    'external_order_id' => $orderData['external_order_id'],
                    'purchase_units' => [
                        'currency' => $orderData['currency'] ?? 'GEL',
                        'total_amount' => $orderData['amount'],
                        'basket' => $orderData['basket'] ?? [],
                    ],
                    'redirect_url' => $orderData['redirect_url'] ?? null,
                    'shop_order_id' => $orderData['shop_order_id'] ?? $orderData['external_order_id'],
                    'locale' => $orderData['locale'] ?? 'ka',
                    'card_binding' => [
                        'intent' => $orderData['card_binding_intent'] ?? 'FUTURE_PAYMENTS', // FUTURE_PAYMENTS or AUTOMATIC_PAYMENTS
                    ],
                    'show_shop_order_id_on_extract' => $orderData['show_shop_order_id_on_extract'] ?? false,
                ]);

            if ($response->failed()) {
                Log::error('BOG Create Order with Card Binding failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_data' => $orderData,
                ]);
                throw new Exception('Failed to create payment order with card binding: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('BOG Create Order with Card Binding error', [
                'error' => $e->getMessage(),
                'order_data' => $orderData,
            ]);
            throw $e;
        }
    }

    /**
     * Execute payment with saved card
     * 
     * @param array $paymentData
     * @return array
     */
    public function executeCardPayment(array $paymentData): array
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/payments/v1/ecommerce/orders", [
                    'callback_url' => $paymentData['callback_url'],
                    'external_order_id' => $paymentData['external_order_id'],
                    'purchase_units' => [
                        'currency' => $paymentData['currency'] ?? 'GEL',
                        'total_amount' => $paymentData['amount'],
                        'basket' => $paymentData['basket'] ?? [],
                    ],
                    'shop_order_id' => $paymentData['shop_order_id'] ?? $paymentData['external_order_id'],
                    'locale' => $paymentData['locale'] ?? 'ka',
                    'card_id' => $paymentData['card_id'],
                    'initiator' => $paymentData['initiator'] ?? 'CUSTOMER', // CUSTOMER or MERCHANT
                ]);

            if ($response->failed()) {
                Log::error('BOG Execute Card Payment failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payment_data' => $paymentData,
                ]);
                throw new Exception('Failed to execute card payment: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('BOG Execute Card Payment error', [
                'error' => $e->getMessage(),
                'payment_data' => $paymentData,
            ]);
            throw $e;
        }
    }

    /**
     * Delete saved card
     * 
     * @param string $cardId
     * @return bool
     */
    public function deleteCard(string $cardId): bool
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withToken($token)
                ->delete("{$this->baseUrl}/payments/v1/ecommerce/cards/{$cardId}");

            if ($response->failed()) {
                Log::error('BOG Delete Card failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'card_id' => $cardId,
                ]);
                return false;
            }

            return true;
        } catch (Exception $e) {
            Log::error('BOG Delete Card error', [
                'error' => $e->getMessage(),
                'card_id' => $cardId,
            ]);
            return false;
        }
    }

    /**
     * Complete pre-authorization
     * 
     * @param string $orderId
     * @param float $amount
     * @return array
     */
    public function completePreAuth(string $orderId, float $amount): array
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/payments/v1/ecommerce/orders/{$orderId}/completion", [
                    'amount' => $amount,
                ]);

            if ($response->failed()) {
                Log::error('BOG Complete Pre-Auth failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_id' => $orderId,
                ]);
                throw new Exception('Failed to complete pre-authorization');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('BOG Complete Pre-Auth error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            throw $e;
        }
    }

    /**
     * Refund payment
     * 
     * @param string $orderId
     * @param float $amount
     * @return array
     */
    public function refundPayment(string $orderId, float $amount): array
    {
        $token = $this->getAccessToken();

        try {
            $response = Http::withToken($token)
                ->post("{$this->baseUrl}/payments/v1/ecommerce/orders/{$orderId}/refund", [
                    'amount' => $amount,
                ]);

            if ($response->failed()) {
                Log::error('BOG Refund Payment failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'order_id' => $orderId,
                ]);
                throw new Exception('Failed to refund payment');
            }

            return $response->json();
        } catch (Exception $e) {
            Log::error('BOG Refund Payment error', [
                'error' => $e->getMessage(),
                'order_id' => $orderId,
            ]);
            throw $e;
        }
    }

    /**
     * Verify callback signature
     * 
     * @param array $callbackData
     * @param string $signature
     * @return bool
     */
    public function verifyCallbackSignature(array $callbackData, string $signature): bool
    {
        // BOG uses HMAC SHA256 for callback verification
        $dataString = json_encode($callbackData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $expectedSignature = hash_hmac('sha256', $dataString, $this->secretKey);
        
        return hash_equals($expectedSignature, $signature);
    }
}

