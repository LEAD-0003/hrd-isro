<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\TrainingCalendarResource\Pages;
use App\Models\Training;
use App\Models\TrainingApplication;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;

class TrainingCalendarResource extends Resource
{
    protected static ?string $model = Training::class;
    protected static ?string $navigationLabel = 'Training Calendar';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 2;
    protected static bool $shouldRegisterNavigation = false;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trainingType.name') 
                    ->label('Category')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('title') 
                    ->label('Training Programme')
                    ->searchable()
                    ->weight('bold')
                    ->wrap(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Registration Ends')
                    ->date('d/m/Y')
                    ->color('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mode')
                    ->label('Mode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Online' => 'warning',
                        'Offline' => 'success',
                        default => 'gray',
                    }),
            ])
            ->defaultSort('start_date', 'asc')
            ->actions([
                Tables\Actions\Action::make('apply')
                    ->label(fn (Training $record) => Carbon::parse($record->start_date)->isPast() ? 'Expired' : 'Apply Now')
                    ->icon('heroicon-o-pencil-square')
                    ->color(fn (Training $record) => Carbon::parse($record->start_date)->isPast() ? 'gray' : 'success')
                    
                    ->requiresConfirmation()
                    ->modalHeading('Training Details & Confirmation')
                    ->modalSubmitActionLabel('Proceed to Apply')
                    
                    ->infolist([
                        Section::make('Training Overview')
                            ->schema([
                                TextEntry::make('title')->label('Programme Name')->weight('bold'),
                                TextEntry::make('trainingType.name')->label('Category'),
                                TextEntry::make('start_date')->label('Starts On')->date('d/m/Y'),
                                TextEntry::make('end_date')->label('Ends On')->date('d/m/Y'),
                                TextEntry::make('mode')->label('Mode of Training'),
                                TextEntry::make('location')->label('Venue/Location')->icon('heroicon-m-map-pin'),
                            ])->columns(2)
                    ])

                    ->disabled(fn (Training $record) => 
                        TrainingApplication::where('user_id', auth()->id())
                            ->where('training_id', $record->id)
                            ->exists() || Carbon::parse($record->start_date)->isPast()
                    )
                    
                    ->action(function (Training $record) {
                        if (Carbon::parse($record->start_date)->isPast()) {
                            Notification::make()
                                ->title('Registration Closed')
                                ->danger()
                                ->send();
                            return;
                        }

                        return redirect()->to(route('filament.employee.resources.trainings.create', [
                            'training_id' => $record->id,
                            'employee_id' => auth()->id(),
                            'employee_name' => auth()->user()->name,
                        ]));
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTrainingCalendars::route('/'),
        ];
    }
}