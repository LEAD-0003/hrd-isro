<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\TrainingFeedback;

class FeedbackStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make(
                'Total Feedback',
                TrainingFeedback::count()
            )
                ->description('Total submitted feedback')
                ->icon('heroicon-o-chat-bubble-left-right')
                ->color('primary'),

            Stat::make(
                'Average Rating',
                round(TrainingFeedback::avg('overall_rating'), 2)
            )
                ->description('Overall satisfaction')
                ->icon('heroicon-o-star')
                ->color('warning'),

            Stat::make(
                'Highest Rating',
                TrainingFeedback::max('overall_rating')
            )
                ->description('Best training rating')
                ->icon('heroicon-o-trophy')
                ->color('success'),

        ];
    }
}
