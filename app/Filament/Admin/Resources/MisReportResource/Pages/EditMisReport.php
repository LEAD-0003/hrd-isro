<?php

namespace App\Filament\Admin\Resources\MisReportResource\Pages;

use App\Filament\Admin\Resources\MisReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMisReport extends EditRecord
{
    protected static string $resource = MisReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
