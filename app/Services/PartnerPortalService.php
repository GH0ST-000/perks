<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PremiumOffer;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PartnerPortalService
{
    public function resolvePartner(): ?Partner
    {
        return auth()->user()?->partner?->load(['categories']);
    }

    public function getDashboardStats(Partner $partner): array
    {
        $days = 7;
        $start = now()->subDays($days - 1)->startOfDay();
        $previousStart = now()->subDays(($days * 2) - 1)->startOfDay();
        $previousEnd = $start->copy()->subSecond();

        $visitsCount = Visit::query()
            ->where('partner_id', $partner->id)
            ->where('visited_at', '>=', $start)
            ->count();

        $previousVisits = Visit::query()
            ->where('partner_id', $partner->id)
            ->whereBetween('visited_at', [$previousStart, $previousEnd])
            ->count();

        $growth = $previousVisits > 0
            ? (int) round((($visitsCount - $previousVisits) / $previousVisits) * 100)
            : ($visitsCount > 0 ? 100 : 0);

        $chartLabels = [];
        $chartValues = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $chartLabels[] = $date->format('d M');
            $chartValues[] = Visit::query()
                ->where('partner_id', $partner->id)
                ->whereDate('visited_at', $date)
                ->count();
        }

        $maxChart = max($chartValues) ?: 1;
        $chartPoints = collect($chartValues)->map(fn (int $v): float => round(($v / $maxChart) * 60, 1))->all();

        return [
            'period_label' => "{$days} დღე",
            'visits_count' => $visitsCount,
            'rating' => $visitsCount > 0 ? min(5, round(4 + min($visitsCount / 500, 1), 1)) : 0,
            'growth' => $growth,
            'chart_labels' => $chartLabels,
            'chart_values' => $chartValues,
            'chart_points' => $chartPoints,
            'demographics' => [
                ['label' => 'კაცი', 'percent' => 45, 'color' => '#3b82f6'],
                ['label' => 'ქალი', 'percent' => 55, 'color' => '#ec4899'],
            ],
        ];
    }

    public function getOffers(Partner $partner): Collection
    {
        return PremiumOffer::query()
            ->where('partner_id', $partner->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getVisitHistory(Partner $partner): Collection
    {
        return Visit::query()
            ->with(['user', 'offerClaim.premiumOffer'])
            ->where('partner_id', $partner->id)
            ->orderByDesc('visited_at')
            ->limit(50)
            ->get();
    }
}
