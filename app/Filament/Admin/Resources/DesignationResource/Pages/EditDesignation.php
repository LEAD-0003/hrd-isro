<?php

namespace App\Filament\Admin\Resources\DesignationResource\Pages;

use App\Filament\Admin\Resources\DesignationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDesignation extends EditRecord
{
    protected static string $resource = DesignationResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
