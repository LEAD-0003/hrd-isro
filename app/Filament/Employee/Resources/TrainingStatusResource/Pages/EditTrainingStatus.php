<?php

namespace App\Filament\Employee\Resources\TrainingStatusResource\Pages;

use App\Filament\Employee\Resources\TrainingStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingStatus extends EditRecord
{
    protected static string $resource = TrainingStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
