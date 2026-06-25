<?php

namespace App\Services;

use App\Models\BogPayment;
use App\Models\Partner;
use App\Models\PartnerMarketingSubscription;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PartnerMarketingSubscriptionService
{
    public function __construct(
        private PartnerMarketingService $marketing,
        private BogPaymentService $bogService,
    ) {}

    public function initiate(Partner $partner, User $user, string $packageId): RedirectResponse
    {
        $package = $this->marketing->package($packageId);

        if (! $package) {
            return back()->with('error', 'პაკეტი ვერ მოიძებნა.');
        }

        if ($partner->marketingSubscriptions()->where('status', PartnerMarketingSubscription::STATUS_ACTIVE)->exists()) {
            return back()->with('error', 'თქვენ უკვე გაქვთ აქტიური მარკეტინგის გამოწერა. ჯერ გააუქმეთ მიმდინარე პაკეტი.');
        }

        try {
            DB::beginTransaction();

            $partner->marketingSubscriptions()
                ->where('status', PartnerMarketingSubscription::STATUS_PENDING)
                ->delete();

            $subscription = PartnerMarketingSubscription::create([
                'partner_id' => $partner->id,
                'user_id' => $user->id,
                'package_id' => $package['id'],
                'package_title' => $package['title'],
                'amount' => $package['price'],
                'currency' => 'GEL',
                'status' => PartnerMarketingSubscription::STATUS_PENDING,
            ]);

            $externalOrderId = 'PERKS-PMKT-'.Str::upper(Str::random(10)).'-'.time();

            $payment = BogPayment::create([
                'user_id' => $user->id,
                'external_order_id' => $externalOrderId,
                'type' => 'partner_marketing_initial',
                'amount' => $package['price'],
                'currency' => 'GEL',
                'status' => 'pending',
                'description' => "მარკეტინგის პაკეტი: {$package['title']}",
                'partner_marketing_subscription_id' => $subscription->id,
            ]);

            $bogResponse = $this->bogService->createOrderWithCardBinding([
                'callback_url' => route('partner.marketing.callback'),
                'redirect_url' => route('partner.marketing.success', $subscription),
                'external_order_id' => $externalOrderId,
                'amount' => (float) $package['price'],
                'currency' => 'GEL',
                'basket' => [
                    [
                        'product_id' => 'partner_marketing_'.$package['id'],
                        'quantity' => 1,
                        'unit_price' => (float) $package['price'],
                        'product_name' => $package['title'],
                    ],
                ],
                'locale' => 'ka',
                'card_binding_intent' => 'AUTOMATIC_PAYMENTS',
            ]);

            $payment->update([
                'bog_order_id' => $bogResponse['id'] ?? null,
                'bog_response' => $bogResponse,
                'status' => 'processing',
            ]);

            DB::commit();

            return redirect($bogResponse['_links']['redirect']['href']);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Partner marketing subscription initiation failed', [
                'partner_id' => $partner->id,
                'package_id' => $packageId,
                'error' => $e->getMessage(),
            ]);

            $message = config('app.debug')
                ? 'გადახდის დაწყება ვერ მოხერხდა: '.$e->getMessage()
                : 'გადახდის დაწყება ვერ მოხერხდა. სცადეთ ხელახლა.';

            return back()->with('error', $message);
        }
    }

    public function handleCallback(array $callbackData): void
    {
        $externalOrderId = $callbackData['external_order_id'] ?? null;

        if (! $externalOrderId) {
            return;
        }

        $payment = BogPayment::query()
            ->where('external_order_id', $externalOrderId)
            ->whereIn('type', ['partner_marketing_initial', 'partner_marketing_recurring'])
            ->first();

        if (! $payment) {
            return;
        }

        $status = $callbackData['status'] ?? null;
        $orderId = $callbackData['order_id'] ?? null;
        $cardId = $callbackData['card_id'] ?? null;

        $payment->update([
            'bog_order_id' => $orderId ?? $payment->bog_order_id,
            'callback_data' => $callbackData,
            'card_id' => $cardId ?? $payment->card_id,
        ]);

        if (in_array($status, ['success', 'COMPLETED'], true)) {
            $this->handleSuccessfulPayment($payment, $callbackData);

            return;
        }

        if (in_array($status, ['failed', 'FAILED'], true)) {
            $payment->update(['status' => 'failed']);

            if ($payment->type === 'partner_marketing_initial' && $payment->partnerMarketingSubscription) {
                $payment->partnerMarketingSubscription->update([
                    'status' => PartnerMarketingSubscription::STATUS_CANCELLED,
                    'cancelled_at' => now(),
                ]);
            }

            if ($payment->type === 'partner_marketing_recurring' && $payment->partnerMarketingSubscription) {
                $payment->partnerMarketingSubscription->update([
                    'status' => PartnerMarketingSubscription::STATUS_PAST_DUE,
                ]);
            }
        }
    }

    public function processRecurring(PartnerMarketingSubscription $subscription): void
    {
        if (! $subscription->isActive() || ! $subscription->bog_card_id) {
            return;
        }

        try {
            DB::beginTransaction();

            $externalOrderId = 'PERKS-PMKT-REC-'.Str::upper(Str::random(10)).'-'.time();

            $payment = BogPayment::create([
                'user_id' => $subscription->user_id,
                'external_order_id' => $externalOrderId,
                'type' => 'partner_marketing_recurring',
                'amount' => $subscription->amount,
                'currency' => $subscription->currency,
                'status' => 'pending',
                'description' => "ყოველთვიური გადახდა: {$subscription->package_title}",
                'partner_marketing_subscription_id' => $subscription->id,
                'card_id' => $subscription->bog_card_id,
            ]);

            $bogResponse = $this->bogService->executeCardPayment([
                'callback_url' => route('partner.marketing.callback'),
                'external_order_id' => $externalOrderId,
                'amount' => (float) $subscription->amount,
                'currency' => $subscription->currency,
                'basket' => [
                    [
                        'product_id' => 'partner_marketing_recurring_'.$subscription->package_id,
                        'quantity' => 1,
                        'unit_price' => (float) $subscription->amount,
                        'product_name' => $subscription->package_title,
                    ],
                ],
                'card_id' => $subscription->bog_card_id,
                'initiator' => 'MERCHANT',
                'locale' => 'ka',
            ]);

            $payment->update([
                'bog_order_id' => $bogResponse['id'] ?? null,
                'bog_response' => $bogResponse,
                'status' => 'processing',
            ]);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Partner marketing recurring payment failed', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);

            $subscription->update(['status' => PartnerMarketingSubscription::STATUS_PAST_DUE]);
        }
    }

    public function cancel(PartnerMarketingSubscription $subscription): void
    {
        $subscription->cancel();
    }

    private function handleSuccessfulPayment(BogPayment $payment, array $callbackData): void
    {
        if ($payment->isCompleted()) {
            return;
        }

        $subscription = $payment->partnerMarketingSubscription;

        if (! $subscription) {
            return;
        }

        try {
            DB::beginTransaction();

            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'payment_method' => $callbackData['payment_method'] ?? 'card',
            ]);

            $paymentMethodId = null;

            if (! empty($callbackData['card_id'])) {
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

                $paymentMethodId = $paymentMethod->id;
            }

            if ($payment->type === 'partner_marketing_initial') {
                $subscription->activate(
                    $callbackData['card_id'] ?? $subscription->bog_card_id ?? '',
                    $paymentMethodId
                );
            } else {
                $subscription->renewBillingPeriod();

                if (! empty($callbackData['card_id'])) {
                    $subscription->update([
                        'bog_card_id' => $callbackData['card_id'],
                        'payment_method_id' => $paymentMethodId,
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Partner marketing payment success handling failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
