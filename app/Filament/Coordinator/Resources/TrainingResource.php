<?php

namespace App\Filament\Coordinator\Resources;

use App\Filament\Coordinator\Resources\TrainingResource\Pages;
use App\Filament\Coordinator\Resources\TrainingResource\RelationManagers;
use App\Models\Training;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;

class TrainingResource extends Resource
{
    protected static ?string $model = Training::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function canCreate(): bool
    {
        return false;
    }


    public static function getEloquentQuery(): Builder
    {
        $centreId = auth()->user()->centre_id;

        return parent::getEloquentQuery()
            ->where('last_date_to_apply', '>=', now()->startOfDay())
            ->whereRaw(
                "JSON_CONTAINS(seat_distribution, JSON_OBJECT('centre_id', ?), '$')",
                [(string) $centreId]
            );
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Training Title')
                    ->searchable(),

                TextColumn::make('last_date_to_apply')
                    ->label('Last Date')
                    ->date(),

                TextColumn::make('start_date')
                    ->date(),

                TextColumn::make('end_date')
                    ->date(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainings::route('/'),
            'create' => Pages\CreateTraining::route('/create'),
            'edit' => Pages\EditTraining::route('/{record}/edit'),
        ];
    }
}