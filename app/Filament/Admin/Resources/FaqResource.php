<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';
    protected static ?int $navigationSort = 11;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('FAQ Management')
                ->schema([
                    Forms\Components\Repeater::make('faq_entries')
                        ->label('Add Multiple FAQs')
                        ->hidden(fn(?Faq $record) => $record !== null)
                        ->schema([
                            Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
                            Forms\Components\RichEditor::make('answer')->required()->columnSpanFull(),
                            Forms\Components\Hidden::make('sort_order')->default(0),
                            Forms\Components\Toggle::make('is_active')->label('Visible')->default(true),
                        ])
                        ->createItemButtonLabel('Add Another FAQ')
                        ->columnSpanFull(),

                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
                        Forms\Components\RichEditor::make('answer')->required()->columnSpanFull(),
                    ])
                        ->visible(fn(?Faq $record) => $record !== null)
                        ->columnSpanFull(),

                Forms\Components\FileUpload::make('attachment')
                    ->label('Upload Document')
                    ->helperText('Allowed files: PDF, DOC, DOCX, JPEG, JPG, PNG. Max size: 10MB.')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/jpg', 
                        'image/png'
                    ])
                    ->maxSize(10240)
                    ->directory('faq-documents')
                    ->columnSpanFull(),
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')->searchable()->wrap()->weight('bold'),
                Tables\Columns\TextColumn::make('answer')->searchable()->html()->wrap()->weight('bold'),

                Tables\Columns\TextColumn::make('attachment')
                    ->label('Document')
                    ->formatStateUsing(fn($state) => $state ? 'View File' : '—')
                    ->color(fn($state) => $state ? 'primary' : 'gray')
                    ->url(fn($record) => $record->attachment ? Storage::url($record->attachment) : null)
                    ->openUrlInNewTab(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Visible to Users'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}