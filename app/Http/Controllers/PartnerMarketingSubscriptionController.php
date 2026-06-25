<?php

namespace App\Http\Controllers;

use App\Models\PartnerMarketingSubscription;
use App\Services\PartnerMarketingSubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PartnerMarketingSubscriptionController extends Controller
{
    public function __construct(
        private PartnerMarketingSubscriptionService $subscriptions,
    ) {}

    public function handleCallback(Request $request): JsonResponse
    {
        Log::info('BOG Partner Marketing Callback received', $request->all());

        try {
            $this->subscriptions->handleCallback($request->all());

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Partner marketing callback failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);

            return response()->json(['error' => 'Callback processing failed'], 500);
        }
    }

    public function success(PartnerMarketingSubscription $subscription)
    {
        $partner = app(\App\Services\PartnerPortalService::class)->resolvePartner();

        if (! $partner || $subscription->partner_id !== $partner->id) {
            abort(403);
        }

        return view('partner.marketing-success', [
            'partner' => $partner,
            'subscription' => $subscription->fresh(),
        ]);
    }

    public function cancel(PartnerMarketingSubscription $subscription)
    {
        $partner = app(\App\Services\PartnerPortalService::class)->resolvePartner();

        if (! $partner || $subscription->partner_id !== $partner->id || ! $subscription->isActive()) {
            abort(403);
        }

        $this->subscriptions->cancel($subscription);

        return redirect()
            ->route('partner.marketing')
            ->with('success', 'გამოწერა გაუქმებულია. მიმდინარე პერიოდის ბოლომდე პაკეტი აქტიური დარჩება.');
    }
}
