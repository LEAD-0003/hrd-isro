<?php

namespace App\Filament\Employee\Widgets;

use App\Models\Training;
use App\Models\TrainingApplication;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;
use Carbon\Carbon;

// class TrainingCalendar extends BaseWidget
// {
//     protected int | string | array $columnSpan = 'full'; 

//     public function table(Table $table): Table
//     {
//         return $table
//             ->query(Training::query()->where('start_date', '>=', now())->orderBy('start_date', 'asc'))
//             ->heading(new HtmlString('<div style="color: #1E9CD7; display: flex; align-items: center; gap: 0.5rem;"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Upcoming Training Calendar</div>'))
//             ->columns([
//                 Tables\Columns\TextColumn::make('title')
//                     ->label('Training Name')
//                     ->weight('bold')
//                     ->wrap()
//                     ->searchable(),
                
//                 Tables\Columns\TextColumn::make('start_date')
//                     ->label('From')
//                     ->date('d M Y'),
                
//                 Tables\Columns\TextColumn::make('end_date')
//                     ->label('To')
//                     ->date('d M Y'),
                
//                 Tables\Columns\TextColumn::make('location')
//                     ->label('Location'),
                
//                 Tables\Columns\TextColumn::make('mode')
//                     ->label('Mode')
//                     ->badge()
//                     ->color(fn (string $state): string => match ($state) {
//                         'Online' => 'warning',
//                         'Offline' => 'success',
//                         default => 'gray',
//                     }),
//             ])
//             ->actions([
//                 Tables\Actions\Action::make('apply')
//                     ->label('APPLY NOW')
//                     ->button()
//                     ->color('primary')
//                     ->requiresConfirmation()
//                     ->disabled(fn (Training $record) => 
//                         TrainingApplication::where('user_id', auth()->id())
//                             ->where('training_id', $record->id)
//                             ->exists() || Carbon::parse($record->start_date)->isPast()
//                     )
//                     // ->url(fn (Training $record): string => route('filament.employee.resources.trainings.create', [
//                     //     'training_id' => $record->id
//                     // ])),
//             ]);
//     }
// } 