<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessRecurringSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process-recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process recurring subscription payments that are due';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionController $subscriptionController)
    {
        $this->info('Processing recurring subscriptions...');

        // Get subscriptions that need to be renewed today
        $subscriptions = Subscription::where('status', 'active')
            ->whereDate('next_billing_date', '<=', now())
            ->whereNotNull('bog_card_id')
            ->get();

        $this->info("Found {$subscriptions->count()} subscriptions to process.");

        $processed = 0;
        $failed = 0;

        foreach ($subscriptions as $subscription) {
            try {
                $this->info("Processing subscription #{$subscription->id} for user #{$subscription->user_id}");
                
                $subscriptionController->processRecurringPayment($subscription);
                
                $processed++;
                $this->info("✓ Successfully processed subscription #{$subscription->id}");
                
            } catch (\Exception $e) {
                $failed++;
                $this->error("✗ Failed to process subscription #{$subscription->id}: {$e->getMessage()}");
                
                Log::error('Recurring subscription processing failed', [
                    'subscription_id' => $subscription->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->info("\nProcessing complete!");
        $this->info("Processed: {$processed}");
        $this->info("Failed: {$failed}");

        return Command::SUCCESS;
    }
}

