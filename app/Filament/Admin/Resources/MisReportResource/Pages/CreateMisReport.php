<?php

namespace App\Filament\Admin\Resources\MisReportResource\Pages;

use App\Filament\Admin\Resources\MisReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMisReport extends CreateRecord
{
    protected static string $resource = MisReportResource::class;
        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
