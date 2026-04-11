<?php

namespace App\Filament\Hq\Resources\TrainingApplicationResource\Pages; 

use App\Filament\Hq\Resources\TrainingApplicationResource; 
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingApplication extends EditRecord
{
    protected static string $resource = TrainingApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
