<?php

namespace App\Filament\Admin\Resources\CentreResource\Pages;

use App\Filament\Admin\Resources\CentreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCentres extends ListRecords
{
    protected static string $resource = CentreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
