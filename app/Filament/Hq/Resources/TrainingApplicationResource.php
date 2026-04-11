<?php

namespace App\Filament\Hq\Resources;

use App\Filament\Hq\Resources\TrainingApplicationResource\Pages;
use App\Models\TrainingApplication;
use Dom\Text;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Notifications\Notification;

class TrainingApplicationResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static ?string $navigationLabel = 'Training Approvals';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('training.trainingType.name')
                ->label('Training Type')
                ->badge()
                ->color('gray')
                ->searchable()
                ->sortable(),
                
            Tables\Columns\TextColumn::make('training.title')
                ->label('Training Name')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('nominee_name')
                    ->label('Nominee')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nominee_emp_id')
                    ->label('Emp ID'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Official Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.phone')
                    ->label('Phone Number'),

                // Tables\Columns\TextColumn::make('centre')
                //     ->label('Centre')
                //     ->sortable(),

                Tables\Columns\TextColumn::make('training.title')
                    ->label('Training')
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => match ($state) {
                        'submitted' => 'warning',
                        'recommended' => 'info',
                        'waiting' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'primary',
                        default => 'gray',
                    }),

                // Tables\Columns\TextColumn::make('hq_comments')
                //     ->label('HQ Remarks')
                //     ->limit(30)
                //     ->placeholder('No remarks'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'submitted' => 'Submitted',
                        'recommended' => 'Recommended',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'completed' => 'Completed',
                    ]),
                Tables\Filters\SelectFilter::make('training_id')
                    ->relationship('training', 'title')
                    ->label('Filter by Training'),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->headerActions([
                Tables\Actions\Action::make('export_report')
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Tables\Contracts\HasTable $livewire) {
                        $records = $livewire->getFilteredTableQuery()->with(['user', 'training'])->get();

                        if ($records->isEmpty()) {
                            Notification::make()->title('Nothing to download') . warning() . send();
                            return;
                        }

                        $csvData = [];
                        $csvData[] = ['Nominee Name', 'Emp ID', 'Official Email', 'Phone', 'Centre', 'Training', 'Status', 'HQ Remarks', 'From Date', 'To Date'];

                        foreach ($records as $record) {
                            $csvData[] = [
                                $record->nominee_name,
                                $record->nominee_emp_id,
                                $record->user?->email,
                                $record->user?->phone,
                                $record->user?->centre?->name ?? $record->centre,
                                $record->training?->title,
                                ucfirst($record->status),
                                $record->hq_comments ?? 'N/A',
                                $record->training?->start_date,
                                $record->training?->end_date,
                            ];
                        }

                        $filename = "Approvals_Report_" . now()->format('Ymd_His') . ".csv";
                        $handle = fopen('php://temp', 'r+');
                        foreach ($csvData as $row) {
                            fputcsv($handle, $row);
                        }
                        rewind($handle);
                        $content = stream_get_contents($handle);
                        fclose($handle);

                        return response()->streamDownload(fn() => print($content), $filename, [
                            'Content-Type' => 'text/csv',
                        ]);
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([Forms\Components\Textarea::make('hq_comments')->label('Approval Remarks')])
                    ->visible(fn($record) => in_array($record->status, ['submitted', 'recommended', 'waiting']))
                    ->action(fn($record, $data) => $record->update(['status' => 'approved', 'hq_comments' => $data['hq_comments']])),
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([Forms\Components\Textarea::make('hq_comments')->required()->label('Rejection Reason')])
                    ->visible(fn($record) => in_array($record->status, ['submitted', 'recommended', 'waiting']))
                    ->action(fn($record, $data) => $record->update(['status' => 'rejected', 'hq_comments' => $data['hq_comments']])),
                Tables\Actions\Action::make('complete')
                    ->label('Completed')
                    ->icon('heroicon-o-check-badge')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'approved')
                    ->action(fn($record) => $record->update(['status' => 'completed'])),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Current Training')
                    ->schema([
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('nominee_name')->label('Name'),
                                TextEntry::make('nominee_emp_id')->label('Employee ID'),
                                TextEntry::make('user.email')->label('Official Email'),
                                TextEntry::make('user.phone')->label('Phone'),
                            ]),
                        Grid::make(4)
                            ->schema([
                                TextEntry::make('training.title')->label('Training'),
                                TextEntry::make('training.start_date')->date()->label('Date'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn($state) => match ($state) {
                                        'approved' => 'success',
                                        'completed' => 'primary',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    }),
                                TextEntry::make('hq_comments')->label('HQ Remarks')->placeholder('No remarks'),
                            ]),
                    ]),
                Section::make('Training History')
                    ->schema([
                        RepeatableEntry::make('history')
                            ->state(function ($record) {
                                return TrainingApplication::where('nominee_emp_id', $record->nominee_emp_id)
                                    ->where('id', '!=', $record->id)
                                    ->with('training')
                                    ->latest()
                                    ->get()
                                    ->toArray();
                            })
                            ->schema([
                                TextEntry::make('training.title')->label('Training'),
                                TextEntry::make('training.start_date')->date()->label('Date'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn($state) => match ($state) {
                                        'completed' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    }),
                            ])
                            ->columns(3)
                            ->placeholder('No history found'),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingApplications::route('/'),
        ];
    }
}