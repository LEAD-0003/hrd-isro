<?php

namespace App\Filament\Admin\Resources\CentreResource\Pages;

use App\Filament\Admin\Resources\CentreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCentre extends EditRecord
{
    protected static string $resource = CentreResource::class;

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
