<?php

namespace App\Filament\Employee\Resources\TrainingResource\Pages;

use App\Filament\Employee\Resources\TrainingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTraining extends CreateRecord
{
    protected static string $resource = TrainingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['status'] = 'new'; 

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function mount(): void
        {
            parent::mount();

            $user = auth()->user();

            $this->form->fill([
                'training_id' => request()->query('training_id'),
                'prefix' => $user->prefix,
                'name' => $user->name,
                'employee_code' => $user->employee_code,
                'designation' => $user->designation?->name,
                'centre' => $user->centre?->name,
                'user_id' => $user->id,
            ]);
        }
}
