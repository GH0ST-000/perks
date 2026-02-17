<?php

namespace App\Http\Controllers;

use App\Models\BogPayment;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Services\BogPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function __construct(
        private BogPaymentService $bogService
    ) {}

    /**
     * Show subscription plans
     */
    public function index()
    {
        $user = Auth::user();
        $activeSubscription = $user->subscriptions()->where('status', 'active')->first();
        $paymentMethods = $user->paymentMethods()->get();

        return view('subscriptions.index', compact('activeSubscription', 'paymentMethods'));
    }

    /**
     * Initiate subscription with card saving
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:monthly,yearly',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();

        // Check if user already has active subscription
        if ($user->subscriptions()->where('status', 'active')->exists()) {
            return back()->with('error', 'You already have an active subscription.');
        }

        try {
            DB::beginTransaction();

            // Create subscription record
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'name' => ucfirst($request->plan) . ' Subscription',
                'type' => $request->plan,
                'amount' => $request->amount,
                'currency' => 'GEL',
                'status' => 'active',
                'current_period_start' => now(),
                'current_period_end' => $request->plan === 'monthly' ? now()->addMonth() : now()->addYear(),
                'next_billing_date' => $request->plan === 'monthly' ? now()->addMonth() : now()->addYear(),
            ]);

            // Create initial payment
            $externalOrderId = 'PERKS-SUB-' . Str::upper(Str::random(12)) . '-' . time();
            
            $payment = BogPayment::create([
                'user_id' => $user->id,
                'external_order_id' => $externalOrderId,
                'type' => 'subscription_initial',
                'amount' => $request->amount,
                'currency' => 'GEL',
                'status' => 'pending',
                'description' => "Initial payment for {$subscription->name}",
                'subscription_id' => $subscription->id,
            ]);

            // Create BOG order with card binding
            $orderData = [
                'callback_url' => route('subscription.callback'),
                'redirect_url' => route('subscription.success', ['subscription' => $subscription->id]),
                'external_order_id' => $externalOrderId,
                'amount' => (float) $request->amount,
                'currency' => 'GEL',
                'basket' => [
                    [
                        'product_id' => 'subscription_' . $request->plan,
                        'quantity' => 1,
                        'unit_price' => (float) $request->amount,
                        'product_name' => $subscription->name,
                    ],
                ],
                'locale' => 'ka',
                'card_binding_intent' => 'AUTOMATIC_PAYMENTS', // For recurring payments
            ];

            $bogResponse = $this->bogService->createOrderWithCardBinding($orderData);

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
            Log::error('Subscription initiation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
            ]);

            // Show detailed error in development
            $errorMessage = config('app.debug') 
                ? 'Failed to initiate subscription: ' . $e->getMessage()
                : 'Failed to initiate subscription. Please try again.';

            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Handle subscription callback from BOG
     */
    public function handleCallback(Request $request)
    {
        Log::info('BOG Subscription Callback received', $request->all());

        try {
            $orderId = $request->input('order_id');
            $status = $request->input('status');
            $externalOrderId = $request->input('external_order_id');
            $cardId = $request->input('card_id');

            // Find payment
            $payment = BogPayment::where('external_order_id', $externalOrderId)->first();

            if (!$payment) {
                Log::error('Payment not found', ['external_order_id' => $externalOrderId]);
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Update payment
            $payment->update([
                'bog_order_id' => $orderId,
                'callback_data' => $request->all(),
                'card_id' => $cardId,
            ]);

            // Handle payment status
            if ($status === 'success' || $status === 'COMPLETED') {
                $this->handleSuccessfulSubscription($payment, $request->all());
            } elseif ($status === 'failed' || $status === 'FAILED') {
                $payment->update(['status' => 'failed']);
                
                // Cancel subscription
                if ($payment->subscription) {
                    $payment->subscription->update(['status' => 'cancelled']);
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Subscription callback processing failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }

    /**
     * Handle successful subscription payment
     */
    private function handleSuccessfulSubscription(BogPayment $payment, array $callbackData)
    {
        if ($payment->isCompleted()) {
            return;
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'payment_method' => $callbackData['payment_method'] ?? 'card',
            ]);

            // Save card information
            $paymentMethod = null;
            if (isset($callbackData['card_id'])) {
                $cardData = $callbackData['card'] ?? [];
                
                $paymentMethod = PaymentMethod::updateOrCreate(
                    [
                        'user_id' => $payment->user_id,
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
                        'is_default' => true,
                        'metadata' => $cardData,
                    ]
                );
            }

            // Update subscription with card info
            if ($payment->subscription && $paymentMethod) {
                $payment->subscription->update([
                    'bog_card_id' => $callbackData['card_id'],
                    'payment_method_id' => $paymentMethod->id,
                ]);
            }

            DB::commit();

            Log::info('Subscription processed successfully', [
                'payment_id' => $payment->id,
                'subscription_id' => $payment->subscription_id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to process successful subscription', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id,
            ]);
            throw $e;
        }
    }

    /**
     * Process recurring subscription payment
     */
    public function processRecurringPayment(Subscription $subscription)
    {
        if (!$subscription->isActive() || !$subscription->bog_card_id) {
            Log::warning('Cannot process recurring payment', [
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            // Create payment record
            $externalOrderId = 'PERKS-REC-' . Str::upper(Str::random(12)) . '-' . time();
            
            $payment = BogPayment::create([
                'user_id' => $subscription->user_id,
                'external_order_id' => $externalOrderId,
                'type' => 'subscription_recurring',
                'amount' => $subscription->amount,
                'currency' => $subscription->currency,
                'status' => 'pending',
                'description' => "Recurring payment for {$subscription->name}",
                'subscription_id' => $subscription->id,
                'card_id' => $subscription->bog_card_id,
            ]);

            // Execute payment with saved card
            $paymentData = [
                'callback_url' => route('subscription.callback'),
                'external_order_id' => $externalOrderId,
                'amount' => (float) $subscription->amount,
                'currency' => $subscription->currency,
                'basket' => [
                    [
                        'product_id' => 'subscription_recurring',
                        'quantity' => 1,
                        'unit_price' => (float) $subscription->amount,
                        'product_name' => $subscription->name,
                    ],
                ],
                'card_id' => $subscription->bog_card_id,
                'initiator' => 'MERCHANT',
                'locale' => 'ka',
            ];

            $bogResponse = $this->bogService->executeCardPayment($paymentData);

            // Update payment
            $payment->update([
                'bog_order_id' => $bogResponse['id'] ?? null,
                'bog_response' => $bogResponse,
                'status' => 'processing',
            ]);

            DB::commit();

            Log::info('Recurring payment initiated', [
                'subscription_id' => $subscription->id,
                'payment_id' => $payment->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Recurring payment failed', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id,
            ]);
        }
    }

    /**
     * Cancel subscription
     */
    public function cancel(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $subscription->cancel();

        return back()->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Subscription success page
     */
    public function subscriptionSuccess(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        return view('subscriptions.success', compact('subscription'));
    }

    /**
     * Delete saved payment method
     */
    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            // Delete card from BOG if it has bog_card_id
            if ($paymentMethod->bog_card_id) {
                $this->bogService->deleteCard($paymentMethod->bog_card_id);
            }

            $paymentMethod->delete();

            return back()->with('success', 'Payment method deleted successfully.');

        } catch (\Exception $e) {
            Log::error('Failed to delete payment method', [
                'error' => $e->getMessage(),
                'payment_method_id' => $paymentMethod->id,
            ]);

            return back()->with('error', 'Failed to delete payment method.');
        }
    }
}

