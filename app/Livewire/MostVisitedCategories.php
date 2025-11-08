<?php

namespace App\Livewire;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;

class MostVisitedCategories extends ChartWidget
{
    protected static ?string $heading = 'Most Visited Categories';

    public ?string $filter = 'all';

    protected function getData(): array
    {
        $record = $this->getOwnerRecord();
        
        if (!$record) {
            return [
                'datasets' => [
                    [
                        'label' => 'Visits',
                        'data' => [],
                    ],
                ],
                'labels' => [],
            ];
        }

        $categoryVisits = Visit::where('user_id', $record->id)
            ->whereNotNull('category_id')
            ->selectRaw('category_id, COUNT(*) as visit_count')
            ->groupBy('category_id')
            ->orderByDesc('visit_count')
            ->limit(10)
            ->with('category')
            ->get();

        $labels = $categoryVisits->pluck('category.name')->toArray();
        $data = $categoryVisits->pluck('visit_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(16, 185, 129, 0.5)',
                        'rgba(245, 158, 11, 0.5)',
                        'rgba(239, 68, 68, 0.5)',
                        'rgba(139, 92, 246, 0.5)',
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
