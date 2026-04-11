<?php

namespace App\Filament\Coordinator\Resources;

use App\Filament\Coordinator\Resources\EmployeeResource\Pages;
use App\Models\User;
use App\Models\Designation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class EmployeeResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Employees';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'employee')
            ->where('centre_id', auth()->user()->centre_id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Bulk Employee Upload')
                    ->description(fn() => new \Illuminate\Support\HtmlString(
                        'Create multiple employees at once. Download the <a href="' . asset('storage/sample/employee.csv') . '" class="text-primary-600 font-bold underline" download>Sample CSV Template</a>'
                    ))
                    ->schema([
                        Forms\Components\FileUpload::make('bulk_file')
                            ->label('Upload Excel/CSV File')
                            ->acceptedFileTypes(['text/csv', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (!$state) return;

                                try {
                                    $path = $state->getRealPath();
                                    $data = Excel::toArray([], $path)[0];
                                    array_shift($data);

                                    $createdCount = 0;
                                    $skippedCount = 0;

                                    foreach ($data as $row) {
                                        if (empty($row[7])) continue;

                                        $exists = User::where('employee_code', $row[7])->exists();

                                        if ($exists) {
                                            $skippedCount++;
                                            continue;
                                        }

                                        $designation = Designation::where('name', 'like', '%' . $row[10] . '%')->first();

                                        User::create([
                                            'prefix'        => $row[0],
                                            'name'          => $row[1],
                                            'gender'        => $row[2],
                                            'dob'           => Carbon::parse($row[3])->format('Y-m-d'),
                                            'phone'         => $row[4],
                                            'landline'      => $row[5] ?? null,
                                            'email'         => $row[6],
                                            'employee_code' => $row[7],
                                            'username'      => $row[7],
                                            'password'      => Hash::make($row[7]),
                                            'designation_id' => $designation?->id,
                                            'centre_id'     => auth()->user()->centre_id,
                                            'role'          => 'employee',
                                            'is_active'     => true,
                                        ]);
                                        $createdCount++;
                                    }

                                    Notification::make()
                                        ->title("Import Complete: {$createdCount} created, {$skippedCount} skipped (duplicates).")
                                        ->success()
                                        ->send();

                                    return redirect()->to(EmployeeResource::getUrl('index'));
                                } catch (\Exception $e) {
                                    Notification::make()
                                        ->title('Error: ' . $e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            })
                            ->visible(fn($context) => $context === 'create'),
                    ])
                    ->collapsible()
                    ->visible(fn($context) => $context === 'create'),

                Forms\Components\Section::make('Individual Employee Details')
                    ->schema([
                        Forms\Components\Select::make('prefix')
                            ->options(['Mr' => 'Mr', 'Ms' => 'Ms', 'Mrs' => 'Mrs', 'Dr' => 'Dr'])
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('gender')
                            ->options(['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other'])
                            ->required(),

                        Forms\Components\DatePicker::make('dob')
                            ->label('Date of Birth')
                            ->displayFormat('d/m/Y')
                            ->required()
                            ->native()
                            ->maxDate(now()->subYears(18))
                            ->minDate(now()->subYears(60)),

                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required()
                            ->maxLength(10),

                        Forms\Components\TextInput::make('landline')
                            ->label('Landline (Optional)')
                            ->tel()
                            ->maxLength(15),

                        Forms\Components\TextInput::make('email')
                            ->label('Official Email ID')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->regex('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.gov\.in$/i'),
                    ])->columns(2),

                Forms\Components\Section::make('Official Information')
                    ->schema([
                        Forms\Components\TextInput::make('employee_code')
                            ->label('Employee Code')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, Forms\Set $set) => $set('username', $state)),

                        Forms\Components\Select::make('designation_id')
                            ->label('Designation')
                            ->relationship('designation', 'name')
                            ->preload()
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('centre_id')
                            ->label('Centre')
                            ->relationship('centre', 'name')
                            ->required()
                            ->default(fn() => auth()->user()->centre_id)
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('username')
                            ->label('Login Username')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->readOnly(),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn($state) => Hash::make($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->visible(fn(string $context): bool => $context === 'create'),

                        Forms\Components\Hidden::make('role')->default('employee'),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Personal & Contact Info')
                    ->schema([
                        Infolists\Components\TextEntry::make('prefix')->label('Prefix'),
                        Infolists\Components\TextEntry::make('name')->label('Full Name'),
                        Infolists\Components\TextEntry::make('gender')->label('Gender'),
                        Infolists\Components\TextEntry::make('dob')->label('Date of Birth')->date(),
                        Infolists\Components\TextEntry::make('phone')->label('Mobile'),
                        Infolists\Components\TextEntry::make('landline')->label('Landline')->placeholder('N/A'),
                        Infolists\Components\TextEntry::make('email')->label('Official Email'),
                        Infolists\Components\TextEntry::make('employee_code')->label('Employee Code'),
                        Infolists\Components\TextEntry::make('designation.name')->label('Designation'),
                        Infolists\Components\TextEntry::make('centreRel.name')->label('Centre'),
                    ])->columns(5),

                Infolists\Components\Section::make('Training History')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('training_applications')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('training.title')->label('Training Name'),
                                Infolists\Components\TextEntry::make('training.start_date')->label('Date')->date(),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn($state) => match ($state) {
                                        'completed' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    }),
                            ])->columns(3)->grid(1)
                            ->placeholder('No past training records found.')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')->label('Code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('designation.name')->label('Designation')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->label('Email')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('Phone')->searchable(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status')
                    ->disabled(fn($record) => $record->dob && \Carbon\Carbon::parse($record->dob)->age >= 61),
            ])
            ->headerActions([
                ExportAction::make()
                    ->label('Download Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->exports([
                        ExcelExport::make()
                            ->fromTable()
                            ->except(['is_active']), 
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('designation_id')
                    ->label('Designation')
                    ->relationship('designation', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('employee_code')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Employee Code')
                            ->placeholder('Enter Employee Code ...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn(Builder $query, $value): Builder =>
                            $query->where('employee_code', 'like', "%{$value}%"),
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        if (!filled($data['value'] ?? null)) {
                            return [];
                        }

                        return [
                            'employee_code' => 'Code: ' . $data['value'],
                        ];
                    }),

                Tables\Filters\Filter::make('phone')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Phone')
                            ->placeholder('98765...'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn(Builder $query, $value): Builder =>
                            $query->where('phone', 'like', "%{$value}%"),
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        if (!filled($data['value'] ?? null)) {
                            return [];
                        }

                        return [
                            'phone' => 'Phone: ' . $data['value'],
                        ];
                    }),

                Tables\Filters\Filter::make('email')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Official Email')
                            ->placeholder('abc@isro.gov.in'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn(Builder $query, $value): Builder =>
                            $query->where('email', 'like', "%{$value}%"),
                        );
                    })
                    ->indicateUsing(function (array $data): array {
                        if (!filled($data['value'] ?? null)) {
                            return [];
                        }

                        return [
                            'email' => 'Email: ' . $data['value'],
                        ];
                    }),

                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        '1' => 'Active',
                        '0' => 'Inactive',
                    ]),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(5)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            // ])
            // ->bulkActions([
            //     Tables\Actions\BulkActionGroup::make([
            //         Tables\Actions\DeleteBulkAction::make(),
            //     ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}