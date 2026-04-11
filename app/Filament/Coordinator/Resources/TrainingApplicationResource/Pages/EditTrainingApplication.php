<?php

namespace App\Filament\Coordinator\Resources\TrainingApplicationResource\Pages;

use App\Filament\Coordinator\Resources\TrainingApplicationResource;
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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['nominations'][0])) {
            $nominee = $data['nominations'][0];
            $data['user_id'] = $nominee['user_id'];
            $data['nominee_emp_id'] = $nominee['nominee_emp_id'];
            $data['nominee_name'] = $nominee['nominee_name'];
            $data['nominee_designation'] = $nominee['nominee_designation'];
            $data['nominee_email'] = $nominee['nominee_email'];
            $data['nominee_phone'] = $nominee['nominee_phone'];
        }

        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}