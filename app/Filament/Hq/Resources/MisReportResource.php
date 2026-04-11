<?php

namespace App\Filament\Hq\Resources;

use App\Models\TrainingApplication;
use App\Models\Centre;
use App\Models\Designation;
use App\Models\TrainingType;
use App\Filament\Hq\Resources\MisReportResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

class MisReportResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;
    protected static ?string $navigationLabel = 'Mis Report';
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?int $navigationSort = 9;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('user.employee_code')
                    ->label('Employee Code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.designation.name')
                    ->label('Designation'),

            Tables\Columns\TextColumn::make('training.location')
                ->label('Location'),

                Tables\Columns\TextColumn::make('user.centreRel.name')
                    ->label('Centre'),

                Tables\Columns\TextColumn::make('training.title')
                    ->label('Name of Training')
                    ->color('primary')
                    ->wrap(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'submitted' => 'warning',
                        'recommended' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('training_dates')
                    ->label('Dates')
                    ->state(function (TrainingApplication $record): string {
                        return "From - " . \Carbon\Carbon::parse($record->training->start_date)->format('d/m/Y') . 
                               "\nTo - " . \Carbon\Carbon::parse($record->training->end_date)->format('d/m/Y');
                    })
                    ->wrap(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('centre')
                    ->label('Centre')
                    ->searchable()
                    ->options(Centre::pluck('name', 'id'))
                    ->query(
                        fn(Builder $query, array $data) =>
                        $data['value']
                            ? $query->whereHas('user', fn($q) =>
                            $q->where('centre_id', $data['value']))
                            : null
                    ),

                Tables\Filters\SelectFilter::make('designation')
                    ->label('Designation')
                    ->searchable()
                    ->options(Designation::pluck('name', 'id'))
                    ->query(
                        fn(Builder $query, array $data) =>
                        $data['value']
                            ? $query->whereHas('user', fn($q) =>
                            $q->where('designation_id', $data['value']))
                            : null
                    ),

                Tables\Filters\SelectFilter::make('training_type')
                    ->label('Types of Training')
                    ->searchable()
                    ->options(TrainingType::pluck('name', 'id'))
                    ->query(
                        fn(Builder $query, array $data) =>
                        $data['value']
                            ? $query->whereHas('training', fn($q) =>
                            $q->where('training_type_id', $data['value']))
                            : null
                    ),

                Tables\Filters\SelectFilter::make('year')
                    ->label('Year')
                    ->searchable()
                    ->options(
                        \App\Models\Training::query()
                            ->selectRaw('YEAR(start_date) as year')
                            ->distinct()
                            ->orderBy('year', 'desc')
                            ->pluck('year', 'year')
                            ->toArray()
                    )
                    ->query(
                        fn(Builder $query, array $data) =>
                        $data['value']
                            ? $query->whereHas('training', fn($q) =>
                            $q->whereYear('start_date', $data['value']))
                            : null
                    ),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(4) 
            ->headerActions([
                Tables\Actions\Action::make('direct_export')
                    ->label('Download Report')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Tables\Contracts\HasTable $livewire) {
                        $records = $livewire->getFilteredTableQuery()->with(['user', 'training'])->get();
                        
                        if ($records->isEmpty()) {
                            Notification::make()
                                ->title('Nothing to download')
                                ->warning()
                                ->send();
                            
                            return; 
                        }

                        $csvData = [];
                        $csvData[] = ['Name', 'Employee Code', 'Official Email', 'Phone', 'Location', 'Designation', 'Centre', 'Training', 'Status', 'From Date', 'To Date'];

                        foreach ($records as $record) {
                            $csvData[] = [
                                $record->user?->name,
                                $record->user?->employee_code,
                                $record->user?->email,
                                $record->user?->phone,
                                $record->training?->location,
                                $record->user?->designation?->name,
                                $record->user?->centre?->name,
                                $record->training?->title,
                                $record->status,
                                $record->training?->start_date,
                                $record->training?->end_date,
                            ];
                        }

                        $filename = "MIS_Report_" . now()->format('Ymd_His') . ".csv";
                        $handle = fopen('php://temp', 'r+');
                        foreach ($csvData as $row) {
                            fputcsv($handle, $row);
                        }
                        rewind($handle);
                        $content = stream_get_contents($handle);
                        fclose($handle);

                        return response()->streamDownload(fn () => print($content), $filename, [
                            'Content-Type' => 'text/csv',
                        ]);
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMisReports::route('/'),
        ];
    }
}