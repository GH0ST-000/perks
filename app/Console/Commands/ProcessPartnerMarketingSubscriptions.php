<?php

namespace App\Console\Commands;

use App\Models\PartnerMarketingSubscription;
use App\Services\PartnerMarketingSubscriptionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessPartnerMarketingSubscriptions extends Command
{
    protected $signature = 'partner-marketing:process-recurring';

    protected $description = 'Charge due partner marketing package subscriptions via BOG';

    public function handle(PartnerMarketingSubscriptionService $service): int
    {
        $subscriptions = PartnerMarketingSubscription::query()
            ->where('status', PartnerMarketingSubscription::STATUS_ACTIVE)
            ->whereDate('next_billing_date', '<=', now())
            ->whereNotNull('bog_card_id')
            ->get();

        $this->info("Found {$subscriptions->count()} partner marketing subscriptions to bill.");

        foreach ($subscriptions as $subscription) {
            try {
                $service->processRecurring($subscription);
                $this->info("Billed subscription #{$subscription->id} ({$subscription->package_title})");
            } catch (\Throwable $e) {
                Log::error('Partner marketing recurring command failed', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
                $this->error("Failed subscription #{$subscription->id}: {$e->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}
