<?php

namespace App\Filament\Hq\Resources\TrainingResource\Pages;

use App\Filament\Hq\Resources\TrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTraining extends CreateRecord
{
    protected static string $resource = TrainingResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
