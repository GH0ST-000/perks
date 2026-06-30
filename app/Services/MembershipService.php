<?php

namespace App\Services;

use App\Models\PremiumOffer;
use App\Models\Subscription;
use App\Models\User;

class MembershipService
{
    public const PLAN_MEMBER = 'member';

    public const PLAN_LIMITED = 'limited';

    public function planConfig(string $plan): ?array
    {
        return config("perks.membership_plans.{$plan}");
    }

    public function activeSubscription(User $user): ?Subscription
    {
        return $user->subscriptions()
            ->where('status', Subscription::STATUS_ACTIVE)
            ->whereIn('plan', [self::PLAN_MEMBER, self::PLAN_LIMITED])
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->where(function ($query) {
                $query->whereNull('current_period_end')
                    ->orWhere('current_period_end', '>', now());
            })
            ->latest()
            ->first();
    }

    public function hasActiveMembership(User $user): bool
    {
        return $this->activeSubscription($user) !== null;
    }

    public function canActivateOffers(User $user): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $this->hasActiveMembership($user);
    }

    public function plan(User $user): ?string
    {
        return $this->activeSubscription($user)?->plan;
    }

    public function isMember(User $user): bool
    {
        return $this->plan($user) === self::PLAN_MEMBER;
    }

    public function isLimited(User $user): bool
    {
        return $this->plan($user) === self::PLAN_LIMITED;
    }

    public function cardTypeFor(User $user): ?string
    {
        $plan = $this->plan($user);

        if (! $plan) {
            return null;
        }

        return $this->planConfig($plan)['card_type'] ?? null;
    }

    public function discountFor(PremiumOffer $offer, User $user): float
    {
        $cardType = $this->cardTypeFor($user);

        if ($cardType === 'premium') {
            return (float) $offer->premium_discount;
        }

        if ($cardType === 'standard') {
            return (float) $offer->standard_discount;
        }

        return (float) $offer->standard_discount;
    }

    public function pCoinsForOffer(PremiumOffer $offer, User $user): int
    {
        return $this->pCoinsForCardType($offer, $this->cardTypeFor($user) ?? 'standard');
    }

    public function pCoinsForCardType(PremiumOffer $offer, string $cardType): int
    {
        $base = max(0, (int) $offer->p_coins_reward);

        if ($base === 0) {
            return 0;
        }

        if ($cardType === 'premium') {
            $multiplier = (float) config('perks.membership_plans.limited.p_coins_multiplier', 1.5);

            return (int) floor($base * $multiplier);
        }

        return $base;
    }

    public function planLabel(?string $plan): ?string
    {
        if (! $plan) {
            return null;
        }

        return $this->planConfig($plan)['label'] ?? ucfirst($plan);
    }
}
