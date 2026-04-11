<?php

namespace App\Filament\Hq\Resources\MisReportResource\Pages;

use App\Filament\Hq\Resources\MisReportResource;
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
