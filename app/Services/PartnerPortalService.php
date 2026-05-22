<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\PremiumOffer;
use App\Models\User;
use App\Models\Visit;
use DateTimeInterface;
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
            'demographics' => $this->demographicsForPartner($partner, $start),
        ];
    }

    /**
     * @return list<array{label: string, percent: int, color: string}>
     */
    private function demographicsForPartner(Partner $partner, DateTimeInterface $since): array
    {
        $visitorIds = Visit::query()
            ->where('partner_id', $partner->id)
            ->where('visited_at', '>=', $since)
            ->distinct()
            ->pluck('user_id');

        $male = User::query()
            ->whereIn('id', $visitorIds)
            ->where('gender', User::GENDER_MALE)
            ->count();

        $female = User::query()
            ->whereIn('id', $visitorIds)
            ->where('gender', User::GENDER_FEMALE)
            ->count();

        $total = $male + $female;

        if ($total === 0) {
            return [
                ['label' => 'კაცი', 'percent' => 0, 'color' => '#3b82f6'],
                ['label' => 'ქალი', 'percent' => 0, 'color' => '#ec4899'],
            ];
        }

        return [
            [
                'label' => 'კაცი',
                'percent' => (int) round(($male / $total) * 100),
                'color' => '#3b82f6',
            ],
            [
                'label' => 'ქალი',
                'percent' => (int) round(($female / $total) * 100),
                'color' => '#ec4899',
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

    /**
     * @return array{visits: Collection, period: string, period_label: string}
     */
    public function getVisitHistory(Partner $partner, string $period = '28'): array
    {
        $filters = $this->historyPeriodFilters();
        $config = $filters[$period] ?? $filters['28'];
        $since = $config['since']();

        $visits = Visit::query()
            ->with(['user', 'offerClaim.premiumOffer'])
            ->where('partner_id', $partner->id)
            ->where('visited_at', '>=', $since)
            ->orderByDesc('visited_at')
            ->limit(200)
            ->get();

        return [
            'visits' => $visits,
            'period' => $period,
            'period_label' => $config['label'],
        ];
    }

    /**
     * @return array<string, array{label: string, since: callable(): DateTimeInterface}>
     */
    public function historyPeriodFilters(): array
    {
        return [
            '28' => [
                'label' => '28 დღე',
                'since' => fn () => now()->subDays(28)->startOfDay(),
            ],
            '3' => [
                'label' => '3 თვე',
                'since' => fn () => now()->subMonths(3)->startOfDay(),
            ],
            '6' => [
                'label' => '6 თვე',
                'since' => fn () => now()->subMonths(6)->startOfDay(),
            ],
            '9' => [
                'label' => '9 თვე',
                'since' => fn () => now()->subMonths(9)->startOfDay(),
            ],
            '12' => [
                'label' => '12 თვე',
                'since' => fn () => now()->subMonths(12)->startOfDay(),
            ],
        ];
    }
}
