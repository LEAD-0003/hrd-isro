<?php

namespace App\Filament\Employee\Resources\CompletedTrainingResource\Pages;

use App\Filament\Employee\Resources\CompletedTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompletedTraining extends EditRecord
{
    protected static string $resource = CompletedTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
