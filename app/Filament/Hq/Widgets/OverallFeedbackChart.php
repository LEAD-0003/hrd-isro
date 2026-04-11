<?php

namespace App\Filament\Hq\Widgets;

use App\Models\TrainingApplication;
use Filament\Widgets\ChartWidget;

class OverallFeedbackChart extends ChartWidget
{
    protected static ?string $heading = 'Overall Training Feedback Analysis';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = TrainingApplication::whereNotNull('feedback_rating')
            ->selectRaw('feedback_rating, count(*) as count')
            ->groupBy('feedback_rating')
            ->pluck('count', 'feedback_rating')
            ->toArray();

        $values = [];
        for ($i = 1; $i <= 5; $i++) {
            $values[] = $data[$i] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Feedback Ratings',
                    'data' => $values,
                    'backgroundColor' => ['#ef4444', '#f97316', '#eab308', '#22c55e', '#3b82f6'],
                ],
            ],
            'labels' => ['1-Poor', '2-Fair', '3-Good', '4-Very Good', '5-Excellent'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}