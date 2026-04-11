<?php

namespace App\Filament\Admin\Resources\TrainingTypeResource\Pages;

use App\Filament\Admin\Resources\TrainingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainingType extends CreateRecord
{
    protected static string $resource = TrainingTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
