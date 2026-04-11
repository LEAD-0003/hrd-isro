<?php

namespace App\Filament\Hq\Resources\CoordinatorResource\Pages;

use App\Filament\Admin\Resources\CoordinatorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCoordinator extends CreateRecord
{
    protected static string $resource = CoordinatorResource::class;
    protected static ?string $title = 'Create Coordinator';
        protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
