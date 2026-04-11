<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\CompletedTrainingResource\Pages;
use App\Filament\Employee\Resources\CompletedTrainingResource\RelationManagers;
use App\Models\TrainingApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompletedTrainingResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;
    protected static ?int $navigationSort = 4;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
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
                ->modifyQueryUsing(fn (Builder $query) => 
                    $query->where('user_id', auth()->id())
                        ->where('status', 'completed')) 
                ->columns([
                    Tables\Columns\TextColumn::make('training_name')
                        ->label('Training Name')
                        ->searchable(),
                    Tables\Columns\TextColumn::make('year')
                        ->sortable(),
                    Tables\Columns\TextColumn::make('location'),
                    Tables\Columns\BadgeColumn::make('status')
                        ->color('success'),
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
            'index' => Pages\ListCompletedTrainings::route('/'),
           
        ];
    }
}
