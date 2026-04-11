<?php

namespace App\Filament\Employee\Resources\TrainingStatusResource\Pages;

use App\Filament\Employee\Resources\TrainingStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingStatuses extends ListRecords
{
    protected static string $resource = TrainingStatusResource::class;
    protected static ?string $title = 'Training Status';
}
