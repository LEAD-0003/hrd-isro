<?php

namespace App\Filament\Employee\Widgets;

use App\Models\TrainingApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TrainingStats extends BaseWidget
{
    // protected function getStats(): array
    // {
    //     $userId = auth()->id();

    //     return [
    //         Stat::make('Approved Training', TrainingApplication::where('user_id', $userId)->where('status', 'approved')->count())
    //             ->description('Ready to attend')
    //             ->descriptionIcon('heroicon-m-play')
    //             ->color('primary'),

    //         Stat::make('Completed Training', TrainingApplication::where('user_id', $userId)->where('status', 'completed')->count())
    //             ->description('Successfully finished')
    //             ->descriptionIcon('heroicon-m-check-badge')
    //             ->color('success'),

    //         Stat::make('Pending Approval', TrainingApplication::where('user_id', $userId)->whereIn('status', ['submitted', 'recommended', 'waiting'])->count())
    //             ->description('Waiting for response')
    //             ->descriptionIcon('heroicon-m-clock')
    //             ->color('warning'),
    //     ];
    // }

        public function getStats(): array
        {
            $userId = auth()->id();

            return [
                Stat::make('Training History', TrainingApplication::where('user_id', $userId)->where('status', 'completed')->count())
                    ->description('Trainings attended so far')
                    ->descriptionIcon('heroicon-m-check-badge')
                    ->color('success'),

                Stat::make('Pending Feedback', TrainingApplication::where('user_id', $userId)
                    ->where('status', 'completed')
                    ->whereNull('feedback_rating')->count())
                    ->description('Share your experience')
                    ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                    ->color('warning'),
            ];
        }
}