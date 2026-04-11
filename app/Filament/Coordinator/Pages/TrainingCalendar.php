<?php

namespace App\Filament\Coordinator\Pages;

use Filament\Pages\Page;
use App\Models\Training;
use App\Models\User;
use App\Models\Centre;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\ActionSize;
use Filament\Infolists\Components\TextEntry;

class TrainingCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Training Calendar';
    protected static string $view = 'filament.coordinator.pages.training-calendar';

    public $currentMonth;
    public $currentYear;

    public function mount()
    {
        $this->currentMonth = (int) request('month', Carbon::now()->month);
        $this->currentYear = (int) request('year', Carbon::now()->year);
    }

    public function viewTrainingAction(): Action
    {
        return Action::make('viewTraining')
            ->modalHeading('Training Information')
            ->modalWidth('xl')
            ->slideOver()
            ->record(fn(array $arguments) => Training::find($arguments['record'] ?? null))
            ->infolist(
                fn(Infolist $infolist): Infolist => $infolist
                    ->schema([
                        \Filament\Infolists\Components\Section::make('Basic Details')
                            ->schema([
                                TextEntry::make('title')
                                    ->label('Training Name')
                                    ->weight('black')
                                    ->size('lg')
                                    ->columnSpanFull(),
                                TextEntry::make('mode')
                                    ->label('Mode')
                                    ->badge()
                                    ->color('info'),
                                TextEntry::make('last_date_to_apply')
                                    ->label('Deadline')
                                    ->date()
                                    ->weight('bold')
                                    ->color('danger'),
                                TextEntry::make('max_participants')
                                    ->label('Total Seats')
                                    ->weight('bold'),
                            ])->columns(2),

                        \Filament\Infolists\Components\Section::make('Your Centre Seat Allocation')
                            ->schema([
                                \Filament\Infolists\Components\RepeatableEntry::make('seat_distribution')
                                    ->label('')
                                    ->state(function ($record) {
                                        $userCentreId = auth()->user()->centre_id;
                                        return collect($record->seat_distribution ?? [])
                                            ->filter(fn($seat) => $seat['centre_id'] == $userCentreId)
                                            ->values()
                                            ->toArray();
                                    })
                                    ->schema([
                                        \Filament\Infolists\Components\TextEntry::make('centre_id')
                                            ->label('Centre')
                                            ->formatStateUsing(fn($state) => \App\Models\Centre::find($state)?->name ?? 'N/A')
                                            ->weight('bold')
                                            ->color('primary'),
                                        \Filament\Infolists\Components\TextEntry::make('seats')
                                            ->label('Allocated Seats')
                                            ->badge(),
                                    ])
                                    ->columns(2)
                                    ->grid(2)
                            ]),
                    ])
            )
            ->modalActions([
                \Filament\Actions\Action::make('applyNow')
                    ->label('Apply Now')
                    ->color('success')
                    ->icon('heroicon-m-cursor-arrow-rays')
                    ->url(fn(Training $record) => route('filament.coordinator.resources.training-applications.create', [
                        'training_id' => $record->id,
                        'training_title' => $record->title,
                    ]))
                    ->visible(function (Training $record) {
                        $userCentreId = auth()->user()->centre_id;

                        $hasAllocation = collect($record->seat_distribution ?? [])
                            ->contains(fn($seat) => $seat['centre_id'] == $userCentreId);

                        $isBeforeDeadline = now()->lte($record->last_date_to_apply);

                        return $hasAllocation && $isBeforeDeadline;
                    })
            ])
            ->modalSubmitAction(false);
    }

    public function getCalendarDays()
    {
        $currentCentreId = auth()->user()->centre_id;
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)
            ->startOfMonth()
            ->startOfWeek(Carbon::MONDAY);
        $end = Carbon::create($this->currentYear, $this->currentMonth, 1)
            ->endOfMonth()
            ->endOfWeek(Carbon::SUNDAY);

        $days = [];
        while ($date <= $end) {
            $dayDate = $date->format('Y-m-d');

            $trainings = Training::where('start_date', $dayDate)
                ->get()
                ->filter(function ($training) use ($currentCentreId) {
                    return collect($training->seat_distribution)
                        ->contains(fn($seat) => $seat['centre_id'] == $currentCentreId);
                });

            $days[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $this->currentMonth,
                'trainings' => $trainings,
            ];

            $date->addDay();
        }

        return $days;
    }
}