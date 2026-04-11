<?php

namespace App\Filament\Coordinator\Resources\EmployeeResource\Pages;

use App\Filament\Coordinator\Resources\EmployeeResource;
use App\Models\User;
use App\Models\Designation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;
    protected static ?string $title = 'Employees';


    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Employee'),
        ];
        return [
            Actions\Action::make('importEmployees')
                ->label('Bulk Upload (Excel)')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('excel_file')
                        ->label('Select Excel File')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $file = storage_path('app/public/' . $data['excel_file']);

                    try {
                        // Excel header names: prefix, name, gender, dob, phone, email, employee_code, designation_name
                        $rows = Excel::toArray([], $file)[0];
                        array_shift($rows); // Remove header row

                        foreach ($rows as $row) {
                            $designation = Designation::where('name', 'like', '%' . $row[7] . '%')->first();

                            User::create([
                                'prefix' => $row[0],
                                'name' => $row[1],
                                'gender' => $row[2],
                                'dob' => \Carbon\Carbon::parse($row[3])->format('Y-m-d'),
                                'phone' => $row[4],
                                'email' => $row[5],
                                'employee_code' => $row[6],
                                'username' => $row[6],
                                'password' => Hash::make($row[6]),
                                'designation_id' => $designation?->id ?? null,
                                'centre_id' => auth()->user()->centre_id,
                                'role' => 'employee',
                                'is_active' => true,
                            ]);
                        }

                        Notification::make()->title('Employees uploaded successfully!')->success()->send();
                    } catch (\Exception $e) {
                        Notification::make()->title('Upload failed: ' . $e->getMessage())->danger()->send();
                    }
                }),
            Actions\CreateAction::make(),
        ];
    }

    
}
