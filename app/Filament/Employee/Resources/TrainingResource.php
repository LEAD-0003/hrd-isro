<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\TrainingResource\Pages;
use App\Models\TrainingApplication;
use App\Models\Training;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction; 
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class TrainingResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationLabel = 'Apply for Training';
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Details')
                    ->schema([
                        Forms\Components\Select::make('prefix')
                            ->options(['Mr' => 'Mr', 'Ms' => 'Ms', 'Mrs' => 'Mrs', 'Dr' => 'Dr'])
                            ->default(fn() => auth()->user()->prefix)
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->default(fn() => auth()->user()->name)
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        Forms\Components\TextInput::make('employee_code')
                            ->label('Employee Code')
                            ->default(fn() => auth()->user()->employee_code)
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        Forms\Components\TextInput::make('designation')
                            ->default(fn() => auth()->user()->designation?->name)
                            ->disabled()
                            ->dehydrated(true),

                        Forms\Components\TextInput::make('centre')
                            ->default(fn() => auth()->user()->centre?->name)
                            ->disabled()
                            ->dehydrated(true),
                    ])->columns(2),

                Forms\Components\Section::make('Training Details')
                    ->schema([
                        Forms\Components\Select::make('training_id')
                            ->label('Select Training')
                            ->options(Training::all()->pluck('title', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $training = Training::find($state);
                                if ($training && $training->start_date) {
                                    $set('year', Carbon::parse($training->start_date)->format('Y'));
                                }
                            }),

                        Forms\Components\TextInput::make('year')
                            ->disabled()
                            ->dehydrated(true)
                            ->required(),

                        Forms\Components\Hidden::make('user_id')->default(auth()->id()),
                        Forms\Components\Hidden::make('status')->default('submitted'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('training.title')
                    ->label('Training Name')
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'submitted' => 'Submitted',
                        'recommended' => 'Recommended',
                        'approved' => 'Approved',
                        default => $state
                    })
                    ->color(fn(string $state) => match ($state) {
                        'submitted' => 'warning',
                        'recommended' => 'info',
                        'approved' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('training.start_date')
                    ->label('Date')
                    ->date('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->status === 'submitted'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainings::route('/'),
            // 'create' => Pages\CreateTraining::route('/create'),
            // 'edit' => Pages\EditTraining::route('/{record}/edit'),
        ];
    }
}