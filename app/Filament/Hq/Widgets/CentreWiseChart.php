<?php

namespace App\Filament\Hq\Widgets;

use App\Models\TrainingApplication;
use App\Models\Centre;
use Filament\Widgets\ChartWidget;

class CentreWiseChart extends ChartWidget
{
    protected static ?string $heading = 'Nominations by Centre';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $centres = Centre::pluck('name', 'id');
        $labels = [];
        $counts = [];

        foreach ($centres as $id => $name) {
            $labels[] = $name;
            $counts[] = TrainingApplication::where('centre', $id)->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Applications',
                    'data' => $counts,
                    'backgroundColor' => '#6366f1',
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