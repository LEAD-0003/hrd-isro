<?php

namespace App\Filament\Coordinator\Widgets;

use App\Models\TrainingApplication;
use Filament\Widgets\ChartWidget;

class FeedbackChart extends ChartWidget
{
    protected static ?string $heading = 'Training Feedback Analysis';
    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line'; 
    }

    protected function getData(): array
    {
        $centreId = auth()->user()->centre_id;

        $trend = TrainingApplication::whereHas('user', fn($q) => $q->where('centre_id', $centreId))
            ->whereNotNull('feedback_rating')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTHNAME(created_at) as month, AVG(feedback_rating) as avg')
            ->groupBy('month')
            ->pluck('avg', 'month')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Monthly Avg Satisfaction',
                    'data' => array_values($trend),
                    'borderColor' => '#8b5cf6',
                    'fill' => true,
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                ],
            ],
            'labels' => array_keys($trend),
        ];
    }
    
}