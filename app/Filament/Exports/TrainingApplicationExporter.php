<?php

namespace App\Filament\Exports;

use App\Models\TrainingApplication;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TrainingApplicationExporter extends Exporter
{
    protected static ?string $model = TrainingApplication::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('user.name')->label('Employee Name'),
            ExportColumn::make('user.employee_code')->label('Employee Code'),
            ExportColumn::make('user.designation.name')->label('Designation'),
            ExportColumn::make('user.centreRel.name')->label('Centre'),
            ExportColumn::make('training.title')->label('Training Name'),
            ExportColumn::make('status'),
            ExportColumn::make('training.start_date')->label('From Date'),
            ExportColumn::make('training.end_date')->label('To Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your training application export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
