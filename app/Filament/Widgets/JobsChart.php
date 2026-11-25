<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class JobsChart extends ChartWidget
{
    protected static ?string $heading = 'Losses by Status';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $rows = Project::query()
            ->select('status', DB::raw('count(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            'datasets' => [
                [
                    'label' => 'Losses',
                    'data' => $rows->values()->all(),
                    'backgroundColor' => ['#E85D04', '#FF9F1C', '#3D5A80', '#22c55e', '#64748b', '#ef4444'],
                ],
            ],
            'labels' => $rows->keys()->map(fn ($s) => str_replace('_', ' ', ucfirst($s)))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
