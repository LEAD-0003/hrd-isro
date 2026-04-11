<?php

namespace App\Filament\Coordinator\Widgets;

use App\Models\Training;
use App\Models\TrainingApplication;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CoordinatorStats extends BaseWidget
{
    protected function getStats(): array
    {
        $centreId = auth()->user()->centre_id;

        $allottedSeats = \App\Models\Training::where('start_date', '>=', now()->startOfYear())
            ->get()
            ->sum(fn($t) => collect($t->seat_distribution)->where('centre_id', $centreId)->sum('seats'));

        $totalNominations = TrainingApplication::whereHas('user', fn($q) => $q->where('centre_id', $centreId))
            ->whereYear('created_at', now()->year)
            ->count();

        return [
            Stat::make('Seat Utilization', $totalNominations . ' / ' . $allottedSeats)
                ->description('Seats used this year')
                // ->chart([7, 3, 4, 5, 6, 3, 5, $totalNominations]) 
                ->color($totalNominations < $allottedSeats ? 'warning' : 'success'),

            Stat::make('Pending Actions', TrainingApplication::where('status', 'submitted')
                ->whereHas('user', fn($q) => $q->where('centre_id', $centreId))->count())
                ->description('Requires recommendation')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Completion Rate', function () use ($centreId) {
                $total = TrainingApplication::whereHas('user', fn($q) => $q->where('centre_id', $centreId))->count();
                $done = TrainingApplication::where('status', 'completed')
                    ->whereHas('user', fn($q) => $q->where('centre_id', $centreId))->count();
                return $total > 0 ? number_format(($done / $total) * 100, 0) . '%' : '0%';
            })->description('Trainings successfully finished')->color('success'),

            Stat::make('Centre Rating', number_format(TrainingApplication::whereHas('user', fn($q) => $q->where('centre_id', $centreId))->avg('feedback_rating'), 1))
                ->description('Average feedback score')
                ->descriptionIcon('heroicon-m-star')
                ->color('primary'),
        ];
    }
}