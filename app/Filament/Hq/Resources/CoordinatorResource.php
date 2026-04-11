<?php

namespace App\Filament\Hq\Resources;

use App\Filament\Hq\Resources\CoordinatorResource\Pages;
use App\Models\User;
use App\Models\Designation;
use App\Models\Centre;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CoordinatorResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $breadcrumb = 'HRD Coordinators';
    protected static ?string $navigationLabel = 'HRD Coordinators';
    protected static ?string $pluralLabel = 'HRD Coordinators';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 8;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'coordinator');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Individual Coordinator Details')
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
                            ->label('Phone Number')
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
                            ->regex('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.gov\.in$/i')
                            ->validationMessages([
                                'regex' => 'Please enter a valid official email ID ending with .gov.in.',
                            ]),
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
                            ->preload()
                            ->searchable()
                            ->required(),

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
                            ->required(fn(string $context): bool => $context === 'create'),

                        Forms\Components\Hidden::make('role')
                            ->default('coordinator'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('centreRel.name')->label('Centre')->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('Phone'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active Status')
                    ->disabled(fn($record) => $record->dob && \Carbon\Carbon::parse($record->dob)->age >= 61),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('designation_id')
                    ->label('Designation')
                    ->relationship('designation', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('centre_id')
                    ->label('Centre')
                    ->relationship('centre', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('employee_code')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Employee Code')
                            ->placeholder('Enter Code ...'),
                    ])
                    ->query(fn(Builder $query, array $data) => $query->when($data['value'], fn($q, $v) => $q->where('employee_code', 'like', "%{$v}%")))
                    ->indicateUsing(fn(array $data) => filled($data['value']) ? ['employee_code' => 'Code: ' . $data['value']] : []),

                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options(['1' => 'Active', '0' => 'Inactive']),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCoordinators::route('/'),
            'create' => Pages\CreateCoordinator::route('/create'),
            'edit' => Pages\EditCoordinator::route('/{record}/edit'),
        ];
    }
}