<?php

namespace App\Http\Controllers;

use App\Models\BogPayment;
use App\Models\PCoinPackage;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Services\BogPaymentService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        private BogPaymentService $bogService,
        private WalletService $walletService
    ) {}

    /**
     * Show payment page for P-Coin packages
     */
    public function showPaymentPage()
    {
        $packages = PCoinPackage::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $paymentMethods = Auth::user()->paymentMethods()->get();

        return view('payments.index', compact('packages', 'paymentMethods'));
    }

    /**
     * Initiate one-time payment for P-Coins
     */
    public function initiateOneTimePayment(Request $request)
    {
        $request->validate([
            'package_id' => 'required|exists:p_coin_packages,id',
        ]);

        $user = Auth::user();
        $package = PCoinPackage::findOrFail($request->package_id);

        try {
            DB::beginTransaction();

            // Create payment record
            $externalOrderId = 'PERKS-' . Str::upper(Str::random(12)) . '-' . time();
            
            $payment = BogPayment::create([
                'user_id' => $user->id,
                'external_order_id' => $externalOrderId,
                'type' => 'one_time',
                'amount' => $package->price,
                'currency' => 'GEL',
                'status' => 'pending',
                'description' => "Purchase of {$package->name} ({$package->p_coins} P-Coins)",
            ]);

            // Create BOG order
            $orderData = [
                'callback_url' => route('payment.callback'),
                'redirect_url' => route('payment.success', ['payment' => $payment->id]),
                'external_order_id' => $externalOrderId,
                'amount' => (float) $package->price,
                'currency' => 'GEL',
                'basket' => [
                    [
                        'product_id' => $package->id,
                        'quantity' => 1,
                        'unit_price' => (float) $package->price,
                        'product_name' => $package->name,
                    ],
                ],
                'locale' => 'ka',
            ];

            $bogResponse = $this->bogService->createOrder($orderData);

            // Update payment with BOG order ID
            $payment->update([
                'bog_order_id' => $bogResponse['id'] ?? null,
                'bog_response' => $bogResponse,
                'status' => 'processing',
            ]);

            DB::commit();

            // Redirect to BOG payment page
            return redirect($bogResponse['_links']['redirect']['href']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment initiation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'package_id' => $package->id,
            ]);

            // Show detailed error in development
            $errorMessage = config('app.debug') 
                ? 'Failed to initiate payment: ' . $e->getMessage()
                : 'Failed to initiate payment. Please try again.';

            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Handle payment callback from BOG
     */
    public function handleCallback(Request $request)
    {
        Log::info('BOG Payment Callback received', $request->all());

        try {
            // Verify callback signature if provided
            $signature = $request->header('X-Bog-Signature');
            if ($signature) {
                $isValid = $this->bogService->verifyCallbackSignature(
                    $request->all(),
                    $signature
                );

                if (!$isValid) {
                    Log::error('Invalid callback signature');
                    return response()->json(['error' => 'Invalid signature'], 400);
                }
            }

            $orderId = $request->input('order_id');
            $status = $request->input('status');
            $externalOrderId = $request->input('external_order_id');

            // Find payment
            $payment = BogPayment::where('external_order_id', $externalOrderId)->first();

            if (!$payment) {
                Log::error('Payment not found', ['external_order_id' => $externalOrderId]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Update payment status
            $payment->update([
                'bog_order_id' => $orderId,
                'callback_data' => $request->all(),
            ]);

            // Handle payment status
            if ($status === 'success' || $status === 'COMPLETED') {
                $this->handleSuccessfulPayment($payment, $request->all());
            } elseif ($status === 'failed' || $status === 'FAILED') {
                $payment->update(['status' => 'failed']);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Callback processing failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }

    /**
     * Handle successful payment
     */
    private function handleSuccessfulPayment(BogPayment $payment, array $callbackData)
    {
        if ($payment->isCompleted()) {
            return; // Already processed
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'payment_method' => $callbackData['payment_method'] ?? 'card',
            ]);

            // Get package details from payment description or metadata
            $package = $this->getPackageFromPayment($payment);

            if ($package) {
                // Credit P-Coins to user's wallet
                $this->walletService->credit(
                    $payment->user,
                    $package->p_coins,
                    'purchase',
                    "Purchased {$package->name}",
                    BogPayment::class,
                    $payment->id,
                    ['package_id' => $package->id]
                );
            }

            // Save card if provided in callback
            if (isset($callbackData['card_id'])) {
                $this->savePaymentMethod($payment->user, $callbackData);
            }

            DB::commit();

            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'user_id' => $payment->user_id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process successful payment', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
            throw $e;
        }
    }

    /**
     * Get package from payment
     */
    private function getPackageFromPayment(BogPayment $payment): ?PCoinPackage
    {
        // Try to get package from BOG response basket
        if (isset($payment->bog_response['purchase_units']['basket'][0]['product_id'])) {
            $packageId = $payment->bog_response['purchase_units']['basket'][0]['product_id'];
            return PCoinPackage::find($packageId);
        }

        // Fallback: match by amount
        return PCoinPackage::where('price', $payment->amount)->first();
    }

    /**
     * Save payment method from callback
     */
    private function savePaymentMethod($user, array $callbackData)
    {
        if (!isset($callbackData['card_id'])) {
            return;
        }

        $cardData = $callbackData['card'] ?? [];

        PaymentMethod::updateOrCreate(
            [
                'user_id' => $user->id,
                'bog_card_id' => $callbackData['card_id'],
            ],
            [
                'type' => 'card',
                'brand' => $cardData['brand'] ?? null,
                'last_four' => $cardData['last_four'] ?? substr($callbackData['card_id'], -4),
                'expiry_month' => $cardData['expiry_month'] ?? null,
                'expiry_year' => $cardData['expiry_year'] ?? null,
                'cardholder_name' => $cardData['cardholder_name'] ?? null,
                'is_verified' => true,
                'metadata' => $cardData,
            ]
        );
    }

    /**
     * Payment success page
     */
    public function paymentSuccess(BogPayment $payment)
    {
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.success', compact('payment'));
    }

    /**
     * Payment failure page
     */
    public function paymentFailed(Request $request)
    {
        $orderId = $request->query('order_id');
        $payment = BogPayment::where('bog_order_id', $orderId)->first();

        return view('payments.failed', compact('payment'));
    }
}

