<?php

namespace App\Filament\Hq\Pages;

use Filament\Pages\Page;
use App\Models\Training;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;

class TrainingCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationLabel = 'Training Calendar';
    protected static string $view = 'filament.admin.pages.training-calendar';

    public $currentMonth;
    public $currentYear;

    public function mount()
    {
        $this->currentMonth = (int) request('month', Carbon::now()->month);
        $this->currentYear = (int) request('year', Carbon::now()->year);
    }

    public function viewTrainingAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('viewTraining')
            ->modalHeading('Training Information')
            ->modalWidth('xl')
            ->slideOver()
            ->record(fn (array $arguments) => \App\Models\Training::find($arguments['record'] ?? null))
            ->infolist(fn (\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist => $infolist
                ->schema([
                    \Filament\Infolists\Components\Section::make('Basic Details')
                        ->schema([
                            \Filament\Infolists\Components\TextEntry::make('title')
                                ->label('Training Name')
                                ->weight('black')
                                ->size('lg')
                                ->columnSpanFull(),
                            \Filament\Infolists\Components\TextEntry::make('mode')
                                ->label('Mode')
                                ->badge()
                                ->color('info'),
                            \Filament\Infolists\Components\TextEntry::make('last_date_to_apply')
                                ->label('Deadline')
                                ->date()
                                ->weight('bold')
                                ->color('danger'),
                            \Filament\Infolists\Components\TextEntry::make('max_participants')
                                ->label('Total Seats')
                                ->weight('bold'),
                        ])->columns(2),

                    \Filament\Infolists\Components\Section::make('Centre-wise Seat Distribution')
                        ->schema([
                            \Filament\Infolists\Components\RepeatableEntry::make('seat_distribution')
                                ->label('')
                                ->schema([
                                    \Filament\Infolists\Components\TextEntry::make('centre_id')
                                        ->label('Centre')
                                        ->formatStateUsing(fn ($state) => \App\Models\Centre::find($state)?->name ?? 'N/A')
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
            ->modalSubmitAction(false);
    }

    public function getCalendarDays()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth()->startOfWeek(Carbon::MONDAY);
        $end = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $days = [];
        while ($date <= $end) {
            $dayDate = $date->format('Y-m-d');
            $days[] = [
                'date' => $date->copy(),
                'isCurrentMonth' => $date->month === $this->currentMonth,
                'trainings' => Training::where('start_date', $dayDate)->get(),
            ];
            $date->addDay();
        }
        return $days;
    }
}