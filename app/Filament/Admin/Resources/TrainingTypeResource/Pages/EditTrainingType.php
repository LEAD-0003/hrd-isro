<?php

namespace App\Filament\Admin\Resources\TrainingTypeResource\Pages;

use App\Filament\Admin\Resources\TrainingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingType extends EditRecord
{
    protected static string $resource = TrainingTypeResource::class;

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
