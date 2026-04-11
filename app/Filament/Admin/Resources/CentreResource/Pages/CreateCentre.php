<?php

namespace App\Filament\Admin\Resources\CentreResource\Pages;

use App\Filament\Admin\Resources\CentreResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCentre extends CreateRecord
{
    protected static string $resource = CentreResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
