<?php

namespace App\Filament\Hq\Widgets;

use App\Models\Training; 
use App\Models\TrainingApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStats extends BaseWidget
{
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array 
    {
        return [
            Stat::make('Total Training', Training::count())
                ->description('Programmes available')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('info'),

            Stat::make('Pending Reviews', TrainingApplication::whereIn('status', ['submitted', 'recommended', 'waiting'])->count())
                ->description('Requests awaiting HQ action')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Approved & Ongoing', TrainingApplication::where('status', 'approved')->count())
                ->description('Currently cleared candidates')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Completed', TrainingApplication::where('status', 'completed')->count())
                ->description('Total Training Completed ')
                ->descriptionIcon('heroicon-m-trophy')
                ->color('primary'),
        ];
    }
}