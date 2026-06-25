<?php

namespace App\Http\Controllers;

use App\Models\BogPayment;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use App\Services\BogPaymentService;
use App\Services\MembershipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function __construct(
        private BogPaymentService $bogService,
        private MembershipService $membership,
    ) {}

    /**
     * Show membership plans
     */
    public function index()
    {
        $user = Auth::user();
        $activeSubscription = $this->membership->activeSubscription($user);
        $paymentMethods = $user->paymentMethods()->get();
        $plans = config('perks.membership_plans', []);
        $selectedPlan = request('plan');

        return view('subscriptions.index', compact('activeSubscription', 'paymentMethods', 'plans', 'selectedPlan'));
    }

    /**
     * Initiate membership subscription with card saving
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan' => 'required|in:member,limited',
        ]);

        $user = Auth::user();
        $plan = $request->plan;
        $planConfig = $this->membership->planConfig($plan);

        if (! $planConfig) {
            return back()->with('error', 'არჩეული პაკეტი ვერ მოიძებნა.');
        }

        if ($this->membership->hasActiveMembership($user)) {
            return back()->with('error', 'თქვენ უკვე გაქვთ აქტიური მემბერშიპი.');
        }

        if ($user->subscriptions()->where('status', Subscription::STATUS_PENDING)->exists()) {
            return back()->with('error', 'გაქვთ მიმდინარე გადახდა. დაელოდეთ დასრულებას ან სცადეთ თავიდან.');
        }

        try {
            DB::beginTransaction();

            $amount = (float) $planConfig['amount'];
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'name' => $planConfig['name'],
                'plan' => $plan,
                'type' => 'monthly',
                'amount' => $amount,
                'currency' => 'GEL',
                'status' => Subscription::STATUS_PENDING,
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
                'next_billing_date' => now()->addMonth(),
            ]);

            $externalOrderId = 'PERKS-SUB-'.Str::upper(Str::random(12)).'-'.time();

            $payment = BogPayment::create([
                'user_id' => $user->id,
                'external_order_id' => $externalOrderId,
                'type' => 'subscription_initial',
                'amount' => $amount,
                'currency' => 'GEL',
                'status' => 'pending',
                'description' => "Membership: {$planConfig['name']}",
                'subscription_id' => $subscription->id,
            ]);

            $orderData = [
                'callback_url' => route('subscription.callback'),
                'redirect_url' => route('subscription.success', ['subscription' => $subscription->id]),
                'external_order_id' => $externalOrderId,
                'amount' => $amount,
                'currency' => 'GEL',
                'basket' => [
                    [
                        'product_id' => 'membership_'.$plan,
                        'quantity' => 1,
                        'unit_price' => $amount,
                        'product_name' => $planConfig['name'].' Membership',
                    ],
                ],
                'locale' => 'ka',
                'card_binding_intent' => 'AUTOMATIC_PAYMENTS',
            ];

            $bogResponse = $this->bogService->createOrderWithCardBinding($orderData);

            $payment->update([
                'bog_order_id' => $bogResponse['id'] ?? null,
                'bog_response' => $bogResponse,
                'status' => 'processing',
            ]);

            DB::commit();

            return redirect($bogResponse['_links']['redirect']['href']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Subscription initiation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id,
                'plan' => $plan,
            ]);

            $errorMessage = config('app.debug')
                ? 'Failed to initiate subscription: '.$e->getMessage()
                : 'გამოწერის დაწყება ვერ მოხერხდა. სცადეთ თავიდან.';

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

            $payment = BogPayment::where('external_order_id', $externalOrderId)->first();

            if (! $payment) {
                Log::error('Payment not found', ['external_order_id' => $externalOrderId]);

                return response()->json(['error' => 'Payment not found'], 404);
            }

            $payment->update([
                'bog_order_id' => $orderId,
                'callback_data' => $request->all(),
                'card_id' => $cardId,
            ]);

            if ($status === 'success' || $status === 'COMPLETED') {
                $this->handleSuccessfulSubscription($payment, $request->all());
            } elseif ($status === 'failed' || $status === 'FAILED') {
                $payment->update(['status' => 'failed']);

                if ($payment->subscription) {
                    $payment->subscription->update(['status' => Subscription::STATUS_CANCELLED]);
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
    private function handleSuccessfulSubscription(BogPayment $payment, array $callbackData): void
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

            if ($payment->subscription) {
                $subscription = $payment->subscription;

                if ($payment->type === 'subscription_recurring') {
                    $subscription->renew();
                } elseif ($subscription->isPending()) {
                    $subscription->update([
                        'status' => Subscription::STATUS_ACTIVE,
                        'current_period_start' => now(),
                        'current_period_end' => now()->addMonth(),
                        'next_billing_date' => now()->addMonth(),
                    ]);
                }

                if ($paymentMethod) {
                    $subscription->update([
                        'bog_card_id' => $callbackData['card_id'],
                        'payment_method_id' => $paymentMethod->id,
                    ]);
                }
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
    public function processRecurringPayment(Subscription $subscription): void
    {
        if (! $subscription->isActive() || ! $subscription->bog_card_id) {
            Log::warning('Cannot process recurring payment', [
                'subscription_id' => $subscription->id,
                'status' => $subscription->status,
            ]);

            return;
        }

        try {
            DB::beginTransaction();

            $externalOrderId = 'PERKS-REC-'.Str::upper(Str::random(12)).'-'.time();

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

            $paymentData = [
                'callback_url' => route('subscription.callback'),
                'external_order_id' => $externalOrderId,
                'amount' => (float) $subscription->amount,
                'currency' => $subscription->currency,
                'basket' => [
                    [
                        'product_id' => 'membership_recurring_'.$subscription->plan,
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

    public function cancel(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        $subscription->cancel();

        return back()->with('success', 'მემბერშიპი წარმატებით გაუქმდა.');
    }

    public function subscriptionSuccess(Subscription $subscription)
    {
        if ($subscription->user_id !== Auth::id()) {
            abort(403);
        }

        if ($subscription->isPending() && $subscription->bogPayments()->where('status', 'completed')->exists()) {
            $subscription->update(['status' => Subscription::STATUS_ACTIVE]);
            $subscription->refresh();
        }

        return view('subscriptions.success', compact('subscription'));
    }

    public function deletePaymentMethod(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            if ($paymentMethod->bog_card_id) {
                $this->bogService->deleteCard($paymentMethod->bog_card_id);
            }

            $paymentMethod->delete();

            return back()->with('success', 'გადახდის მეთოდი წაიშალა.');

        } catch (\Exception $e) {
            Log::error('Failed to delete payment method', [
                'error' => $e->getMessage(),
                'payment_method_id' => $paymentMethod->id,
            ]);

            return back()->with('error', 'გადახდის მეთოდის წაშლა ვერ მოხერხდა.');
        }
    }
}
