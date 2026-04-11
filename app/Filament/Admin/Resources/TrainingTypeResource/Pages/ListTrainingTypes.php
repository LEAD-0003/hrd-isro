<?php

namespace App\Filament\Admin\Resources\TrainingTypeResource\Pages;

use App\Filament\Admin\Resources\TrainingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingTypes extends ListRecords
{
    protected static string $resource = TrainingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
