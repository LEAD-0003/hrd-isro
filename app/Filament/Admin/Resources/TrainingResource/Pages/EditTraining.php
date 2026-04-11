<?php

namespace App\Filament\Admin\Resources\TrainingResource\Pages;

use App\Filament\Admin\Resources\TrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTraining extends EditRecord
{
    protected static string $resource = TrainingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->disabled(function () {

                $data = $this->form->getRawState();

                $max = (int) ($data['max_participants'] ?? 0);

                $total = collect($data['seat_distribution'] ?? [])
                    ->sum(fn($row) => (int) ($row['seats'] ?? 0));

                return $total > $max;
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
