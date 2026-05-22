<?php

namespace App\Console\Commands;

use App\Models\Partner;
use App\Services\PartnerAccountService;
use Illuminate\Console\Command;

class SyncPartnerLoginAccounts extends Command
{
    protected $signature = 'partners:sync-login-accounts';

    protected $description = 'Create or update login users for all partners with a phone number';

    public function handle(PartnerAccountService $service): int
    {
        $synced = 0;

        Partner::query()->each(function (Partner $partner) use ($service, &$synced): void {
            if ($service->syncLoginUser($partner)) {
                $synced++;
            }
        });

        $this->info("Synced {$synced} partner login account(s).");

        return self::SUCCESS;
    }
}
