<?php

namespace App\Filament\Hq\Resources\CoordinatorResource\Pages;

use App\Filament\Hq\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoordinator extends EditRecord
{
    protected static string $resource = CoordinatorResource::class;
    protected static ?string $title = 'Edit Coordinator';

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
