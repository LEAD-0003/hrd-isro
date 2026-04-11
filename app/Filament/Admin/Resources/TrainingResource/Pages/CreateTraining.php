<?php

namespace App\Filament\Admin\Resources\TrainingResource\Pages;

use App\Filament\Admin\Resources\TrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTraining extends CreateRecord
{
    protected static string $resource = TrainingResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $max = (int) $data['max_participants'];

        $total = collect($data['seat_distribution'] ?? [])
            ->sum(fn($item) => (int) ($item['seats'] ?? 0));

        if ($total > $max) {

            throw \Illuminate\Validation\ValidationException::withMessages([
                'seat_distribution' => "Total allocated seats ($total) exceed Maximum Seats ($max)."
            ]);
        }

        return $data;
    }
    protected function getCreateFormAction(): \Filament\Actions\Action
    {
        return parent::getCreateFormAction()
            ->disabled(function () {

                $data = $this->form->getRawState();

                $max = (int) ($data['max_participants'] ?? 0);

                $total = collect($data['seat_distribution'] ?? [])
                    ->sum(fn($row) => (int) ($row['seats'] ?? 0));

                return $total > $max;
            });
    }
}
