<?php

namespace App\Filament\Admin\Resources;

use App\Models\EmailTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';
    protected static ?string $navigationLabel = 'Email Settings';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('Templates')
                ->tabs([
                    Tabs\Tab::make('Password Reset')
                        ->icon('heroicon-o-key')
                        ->schema([
                            Forms\Components\TextInput::make('reset_title')->required(),
                            Forms\Components\ColorPicker::make('reset_color')->required(),
                            Forms\Components\RichEditor::make('reset_body')
                                ->helperText('Available: {reset_url}')
                                ->required(),
                        ]),

                    Tabs\Tab::make('New Training (HQ)')
                        ->icon('heroicon-o-megaphone')
                        ->schema([
                            Forms\Components\TextInput::make('pub_title')->required(),
                            Forms\Components\ColorPicker::make('pub_color')->required(),
                            Forms\Components\RichEditor::make('pub_body')
                                ->helperText('Available: {training_title}, {start_date}, {end_date}, {allotted_seats}')
                                ->required(),
                        ]),

                    Tabs\Tab::make('Nomination Received')
                        ->icon('heroicon-o-user-plus')
                        ->schema([
                            Forms\Components\TextInput::make('nom_title')->required(),
                            Forms\Components\ColorPicker::make('nom_color')->required(),
                            Forms\Components\RichEditor::make('nom_body')
                                ->helperText('Available: {nominee_name}, {training_title}')
                                ->required(),
                        ]),

                    Tabs\Tab::make('Nomination Approved')
                        ->icon('heroicon-o-check-circle')
                        ->schema([
                            Forms\Components\TextInput::make('app_title')->required(),
                            Forms\Components\ColorPicker::make('app_color')->required(),
                            Forms\Components\RichEditor::make('app_body')
                                ->helperText('Available: {nominee_name}, {training_title}, {venue}')
                                ->required(),
                        ]),

                    Tabs\Tab::make('Training Completed')
                        ->icon('heroicon-o-chat-bubble-bottom-center-text')
                        ->schema([
                            Forms\Components\TextInput::make('rem_title')->required(),
                            Forms\Components\ColorPicker::make('rem_color')->required(),
                            Forms\Components\RichEditor::make('rem_body')
                                ->helperText('Available: {nominee_name}, {training_title}')
                                ->required(),
                        ]),
                ])->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reset_title')->label('Reset Subject'),
                Tables\Columns\TextColumn::make('pub_title')->label('New Training Subject'),
                Tables\Columns\TextColumn::make('updated_at')->label('Last Updated')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Edit Templates'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Admin\Resources\EmailTemplateResource\Pages\ListEmailTemplates::route('/'),
            'create' => \App\Filament\Admin\Resources\EmailTemplateResource\Pages\CreateEmailTemplate::route('/create'),
            'edit' => \App\Filament\Admin\Resources\EmailTemplateResource\Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}