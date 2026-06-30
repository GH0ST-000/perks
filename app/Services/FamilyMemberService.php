<?php

namespace App\Services;

use App\Models\FamilyMember;
use App\Models\Subscription;
use App\Models\User;

class FamilyMemberService
{
    public function monthlyAddon(): float
    {
        return (float) config('perks.family_member_monthly_addon', 10);
    }

    public function approvedCount(User $user): int
    {
        return $user->familyMembers()->where('status', FamilyMember::STATUS_APPROVED)->count();
    }

    public function calculateSubscriptionAmount(Subscription $subscription): float
    {
        $planConfig = app(MembershipService::class)->planConfig($subscription->plan ?? '');

        $base = (float) ($planConfig['amount'] ?? $subscription->amount);

        $addon = $this->monthlyAddon() * $this->approvedCount($subscription->user);

        return round($base + $addon, 2);
    }

    public function syncUserSubscriptionAmount(User $user): void
    {
        $subscription = app(MembershipService::class)->activeSubscription($user);

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'amount' => $this->calculateSubscriptionAmount($subscription),
        ]);
    }

    public function submit(FamilyMember $member): FamilyMember
    {
        $member->update([
            'status' => FamilyMember::STATUS_PENDING,
            'is_active' => false,
            'approved_at' => null,
            'rejected_at' => null,
        ]);

        return $member->fresh();
    }

    public function approve(FamilyMember $member): FamilyMember
    {
        $member->update([
            'status' => FamilyMember::STATUS_APPROVED,
            'is_active' => true,
            'approved_at' => now(),
            'rejected_at' => null,
        ]);

        $this->syncUserSubscriptionAmount($member->user);

        return $member->fresh();
    }

    public function reject(FamilyMember $member): FamilyMember
    {
        $wasApproved = $member->status === FamilyMember::STATUS_APPROVED;

        $member->update([
            'status' => FamilyMember::STATUS_REJECTED,
            'is_active' => false,
            'rejected_at' => now(),
        ]);

        if ($wasApproved) {
            $this->syncUserSubscriptionAmount($member->user);
        }

        return $member->fresh();
    }

    public function remove(FamilyMember $member): void
    {
        $user = $member->user;
        $wasApproved = $member->status === FamilyMember::STATUS_APPROVED;

        $member->delete();

        if ($wasApproved) {
            $this->syncUserSubscriptionAmount($user);
        }
    }
}
