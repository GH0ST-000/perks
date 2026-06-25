<?php

namespace App\Services;

use App\Models\OfferClaim;
use App\Models\PremiumOffer;
use App\Models\User;

class OfferClaimService
{
    public function createClaim(User $user, PremiumOffer $offer, string $cardType, float $discount): OfferClaim
    {
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
    }

    public function redemptionCodeFor(OfferClaim $claim): string
    {
        return 'P-'.$claim->id;
    }
}
