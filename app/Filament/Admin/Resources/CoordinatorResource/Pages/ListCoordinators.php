<?php

namespace App\Filament\Admin\Resources\CoordinatorResource\Pages;

use App\Filament\Admin\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoordinators extends ListRecords
{
    protected static string $resource = CoordinatorResource::class;
    protected static ?string $title = 'List Coordinators';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Coordinator'),
        ];
    }
}
