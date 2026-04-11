<?php

namespace App\Filament\Admin\Resources\MisReportResource\Pages;

use App\Filament\Admin\Resources\MisReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMisReports extends ListRecords
{
    protected static string $resource = MisReportResource::class;
    protected static ?string $title = 'MIS Report';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
