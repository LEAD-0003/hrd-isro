<?php

namespace App\Filament\Employee\Resources\CompletedTrainingResource\Pages;

use App\Filament\Employee\Resources\CompletedTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompletedTrainings extends ListRecords
{
    protected static string $resource = CompletedTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
