<?php

namespace App\Filament\Coordinator\Widgets;

use App\Models\TrainingApplication;
use Filament\Widgets\ChartWidget;

class StatusChart extends ChartWidget
{
    protected static ?string $heading = 'Application Status Overview';
    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'doughnut'; 
    }

    protected function getData(): array
    {
        $centreId = auth()->user()->centre_id;
        $data = TrainingApplication::whereHas('user', fn($q) => $q->where('centre_id', $centreId))
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')->toArray();

        return [
            'datasets' => [
                [
                    'data' => array_values($data),
                    'backgroundColor' => ['#94a3b8', '#f59e0b', '#10b981', '#ef4444', '#3b82f6'],
                    'hoverOffset' => 4
                ],
            ],
            'labels' => array_map('ucfirst', array_keys($data)),
        ];
    }
   
}