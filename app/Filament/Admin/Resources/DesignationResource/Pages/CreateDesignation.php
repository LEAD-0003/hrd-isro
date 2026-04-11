<?php

namespace App\Filament\Admin\Resources\DesignationResource\Pages;

use App\Filament\Admin\Resources\DesignationResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Designation;
use Filament\Notifications\Notification;

class CreateDesignation extends CreateRecord
{
    protected static string $resource = DesignationResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {

        if (!empty($data['preview_designations'])) {

            $count = 0;

            foreach ($data['preview_designations'] as $row) {

                if (!empty($row['name'])) {

                    if (!Designation::where('name', $row['name'])->exists()) {

                        Designation::create([
                            'name' => trim($row['name'])
                        ]);

                        $count++;
                    }
                }
            }

            Notification::make()
                ->title("$count Designations Imported Successfully")
                ->success()
                ->send();

            return new Designation();
        }

        return Designation::create([
            'name' => $data['name']
        ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
