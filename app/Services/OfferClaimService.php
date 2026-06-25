<?php

namespace App\Services;

use App\Models\OfferClaim;
use App\Models\PremiumOffer;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OfferClaimService
{
    public function __construct(
        private WalletService $wallet,
    ) {}

    public function claimCost(PremiumOffer $offer): int
    {
        return max(0, (int) $offer->p_coins_reward);
    }

    public function userCanAfford(User $user, PremiumOffer $offer): bool
    {
        return $user->p_coins >= $this->claimCost($offer);
    }

    public function createClaim(User $user, PremiumOffer $offer, string $cardType, float $discount): OfferClaim
    {
        $cost = $this->claimCost($offer);

        if (! $this->userCanAfford($user, $offer)) {
            throw new \RuntimeException('insufficient_balance');
        }

        return DB::transaction(function () use ($user, $offer, $cardType, $discount, $cost) {
            if ($cost > 0) {
                $this->wallet->debit(
                    $user,
                    $cost,
                    'purchase',
                    "შეთავაზება: {$offer->name}",
                    PremiumOffer::class,
                    $offer->id,
                );
            }

            $claim = OfferClaim::create([
                'user_id' => $user->id,
                'premium_offer_id' => $offer->id,
                'card_type' => $cardType,
                'discount_received' => $discount,
                'status' => OfferClaim::STATUS_PENDING,
                'claimed_at' => now(),
            ]);

            $claim->update([
                'redemption_code' => $this->redemptionCodeFor($claim),
            ]);

            return $claim->fresh(['premiumOffer.partner']);
        });
    }

    public function redemptionCodeFor(OfferClaim $claim): string
    {
        return 'P-'.$claim->id;
    }
}
