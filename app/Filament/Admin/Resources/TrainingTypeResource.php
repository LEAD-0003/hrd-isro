<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TrainingTypeResource\Pages;
use App\Filament\Admin\Resources\TrainingTypeResource\RelationManagers;
use App\Models\TrainingType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingTypeResource extends Resource
{
    protected static ?string $model = TrainingType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 4;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Type Name')
                    ->required()
                    ->placeholder('e.g., MDP, STP')
                    ->unique(ignoreRecord: true),
            
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Training Type')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTrainingTypes::route('/'),
            'create' => Pages\CreateTrainingType::route('/create'),
            'edit' => Pages\EditTrainingType::route('/{record}/edit'),
        ];
    }
}
