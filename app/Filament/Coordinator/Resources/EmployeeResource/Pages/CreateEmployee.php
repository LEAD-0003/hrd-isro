<?php

namespace App\Filament\Coordinator\Resources\EmployeeResource\Pages;

use App\Filament\Coordinator\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
    protected static ?string $title = 'Create Employee';
    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('employee_code')
                ->label('Employee Code')
                ->required()
                ->unique('users', 'employee_code'),
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
