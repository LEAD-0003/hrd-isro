<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DesignationResource\Pages;
use App\Models\Designation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class DesignationResource extends Resource
{
    protected static ?string $model = Designation::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('Bulk Upload Designations')
                    ->description(fn() => new HtmlString(
                        'Upload Excel / CSV with a single column. Download <a href="' . asset('storage/sample/designation.csv') . '" class="text-primary-600 underline font-bold">Sample Template</a>'
                    ))
                    ->schema([

                        Forms\Components\FileUpload::make('bulk_file')
                            ->label('Excel / CSV File')
                            ->acceptedFileTypes([
                                'text/csv',
                                'application/csv',
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {

                                if (!$state) return;

                                try {

                                    $data = Excel::toArray([], $state->getRealPath())[0];

                                    array_shift($data);

                                    $designations = [];
                                    $names = [];

                                    foreach ($data as $row) {

                                        if (!empty($row[0])) {

                                            $name = trim($row[0]);

                                            $names[] = $name;

                                            $designations[] = [
                                                'name' => $name,
                                                'duplicate' => false,
                                                'exists' => false
                                            ];
                                        }
                                    }

                                    $counts = array_count_values($names);

                                    foreach ($designations as $key => $row) {

                                        if ($counts[$row['name']] > 1) {
                                            $designations[$key]['duplicate'] = true;
                                        }

                                        if (Designation::where('name', $row['name'])->exists()) {
                                            $designations[$key]['exists'] = true;
                                        }
                                    }

                                    $set('preview_designations', $designations);

                                    Notification::make()
                                        ->title(count($designations) . ' rows loaded for preview')
                                        ->success()
                                        ->send();
                                } catch (\Exception $e) {

                                    Notification::make()
                                        ->title('Import Error')
                                        ->body($e->getMessage())
                                        ->danger()
                                        ->send();
                                }
                            }),

                        Forms\Components\Repeater::make('preview_designations')
                            ->label('Preview Designations')
                            ->schema([

                                Forms\Components\TextInput::make('name')
                                    ->label('Designation'),

                                Forms\Components\Placeholder::make('status')
                                    ->label('Status')
                                    ->content(function ($get) {

                                        if ($get('exists')) {
                                            return '⚠ Already Exists ';
                                        }

                                        if ($get('duplicate')) {
                                            return '⚠ Duplicate in File';
                                        }

                                        return 'Ready';
                                    })

                            ])
                            ->columnSpanFull()
                            ->reorderable(false)
                            ->visible(fn($get) => filled($get('preview_designations')))
                            ->default([]),

                    ])
                    ->visible(fn($context) => $context === 'create'),

                Forms\Components\Section::make('Designation')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('Designation Name')
                            ->required(fn($get) => empty($get('preview_designations')))
                            ->unique(ignoreRecord: true)
                            ->visible(fn($get) => empty($get('preview_designations'))),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Designation')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger'),

            ])
            ->filters([

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),

            ])
            ->actions([

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDesignations::route('/'),
            'create' => Pages\CreateDesignation::route('/create'),
            'edit' => Pages\EditDesignation::route('/{record}/edit'),
        ];
    }
}