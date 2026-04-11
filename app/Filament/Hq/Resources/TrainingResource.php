<?php

namespace App\Filament\Hq\Resources;

use App\Filament\Admin\Resources\TrainingResource\Pages;
use App\Models\Training;
use App\Models\TrainingApplication;
use App\Models\Centre;
use App\Models\State;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\HtmlString;

class TrainingResource extends Resource
{
    protected static ?string $model = Training::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Core Training Information')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Training Name')
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\Select::make('training_type_id')
                        ->label('Training Type')
                        ->options(\App\Models\TrainingType::where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->searchable(),

                    Forms\Components\Select::make('state_id')
                        ->label('State')
                        ->options(State::pluck('name', 'id'))
                        ->required()
                        ->searchable(),

                    Forms\Components\TextInput::make('training_institute')
                        ->label('Training Institute')
                        ->required(),

                    Forms\Components\TextInput::make('location')
                        ->label('Venue / City')
                        ->required(),

                    Forms\Components\Select::make('mode')
                        ->options([
                            'On-Site' => 'On-Site',
                            'Online' => 'Online',
                            'Hybrid' => 'Hybrid'
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('max_participants')
                        ->label('Total Maximum Seats')
                        ->numeric()
                        ->live()
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Schedule & Target')
                ->schema([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Attended From')
                        ->required(),

                    Forms\Components\DatePicker::make('end_date')
                        ->label('Attended To')
                        ->required(),

                    Forms\Components\DatePicker::make('last_date_to_apply')
                        ->label('Last Date to Apply')
                        ->required()
                        ->before('start_date'),

                    Forms\Components\Select::make('target_designations')
                        ->label('Target Designations')
                        ->multiple()
                        ->options(\App\Models\Designation::where('is_active', true)->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->columnSpanFull(),
                ])->columns(3),

            Forms\Components\Section::make('Documents & Attachments')
                ->schema([
                    Forms\Components\FileUpload::make('attachments')
                        ->multiple()
                        ->maxFiles(5)
                        ->maxSize(30720)
                        ->disk('public')
                        ->directory('training-docs')
                        ->acceptedFileTypes([
                            'application/pdf',
                            'image/jpeg',
                            'image/png',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ])
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Centre-wise Seat Allocation')
                ->schema([
                    Forms\Components\Repeater::make('seat_distribution')
                        ->live()
                        ->addable(
                            fn(Forms\Get $get) =>
                            collect($get('seat_distribution') ?? [])
                                ->sum(fn($row) => (int) ($row['seats'] ?? 0))
                                < (int) $get('max_participants')
                        )
                        ->schema([
                    Forms\Components\Select::make('centre_id')
                        ->label('Centre')
                        ->options(function (Forms\Get $get) {
                            $allSelected = collect($get('../../seat_distribution') ?? [])
                                ->pluck('centre_id')
                                ->filter()
                                ->toArray();

                            return \App\Models\Centre::query()
                                ->whereNotIn('id', $allSelected)
                                ->pluck('name', 'id');
                        })
                        ->required()
                        ->live() 
                        ->searchable()
                        ->disableOptionsWhenSelectedInSiblingRepeaterItems(), 

                    Forms\Components\TextInput::make('seats')
                                ->numeric()
                                ->required()
                                ->live(),
                        ])
                        ->columns(2),

                    Forms\Components\Placeholder::make('seat_summary.')
                        ->content(function (Forms\Get $get) {
                            $max = (int) $get('max_participants');
                            $total = collect($get('seat_distribution') ?? [])
                                ->sum(fn($row) => (int) ($row['seats'] ?? 0));

                            if ($total > $max) {
                                return new HtmlString("<span style='color:red'>❌ Seat allocation exceeded ($total / $max)</span>");
                            }
                            return "Allocated: $total / $max";
                        })
                        ->extraAttributes(['class' => 'text-sm font-bold'])
                ]), 
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('trainingType.name')
                    ->label('Type')
                    ->badge(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Training Name')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('training_institute')
                    ->label('Institute'),

                Tables\Columns\TextColumn::make('mode')
                    ->badge(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('end_date')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('max_participants')
                    ->label('Seats')
                    ->color('primary'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                Infolists\Components\Section::make('General Information')
                    ->schema([

                        Infolists\Components\TextEntry::make('title')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('training_institute'),

                        Infolists\Components\TextEntry::make('location'),

                        Infolists\Components\TextEntry::make('max_participants')
                            ->badge(),

                        Infolists\Components\TextEntry::make('start_date')
                            ->date(),

                        Infolists\Components\TextEntry::make('end_date')
                            ->date(),



                        Infolists\Components\RepeatableEntry::make('attachments')
                            ->label('Attached Documents')
                            ->getStateUsing(function ($record) {
                                $files = is_string($record->attachments) ? json_decode($record->attachments, true) : $record->attachments;

                                if (empty($files) || !is_array($files)) {
                                    return [];
                                }

                                $formatted = [];
                                foreach ($files as $file) {
                                    $formatted[] = ['file_path' => $file];
                                }
                                return $formatted;
                            })
                            ->schema([
                                Infolists\Components\TextEntry::make('file_path')
                                    ->hiddenLabel()
                                    ->formatStateUsing(fn($state) => basename($state))
                                    ->icon('heroicon-m-arrow-down-tray')
                                    ->color('primary')
                                    ->badge()
                                    ->url(fn($state) => asset('storage/' . $state))
                            ])
                            ->grid(2)
                            ->columnSpanFull(),
                    ])->columns(3),

                Infolists\Components\Section::make('Centre-wise Statistics')
                    ->schema([

                        Infolists\Components\RepeatableEntry::make('seat_distribution')
                            ->schema([

                                Infolists\Components\Grid::make(6)
                                    ->schema([

                                        Infolists\Components\TextEntry::make('centre_id')
                                            ->label('Centre')
                                            ->formatStateUsing(fn($state) => Centre::find($state)?->name),

                                        Infolists\Components\TextEntry::make('seats')
                                            ->label('Seats'),

                                        Infolists\Components\TextEntry::make('centre_id')
                                            ->label('Submitted')
                                            ->formatStateUsing(
                                                fn($state, $record) =>
                                                TrainingApplication::where('training_id', $record->id)
                                                    ->where('centre', $state)
                                                    ->where('status', 'submitted')
                                                    ->count()
                                            ),

                                        Infolists\Components\TextEntry::make('centre_id')
                                            ->label('Approved')
                                            ->formatStateUsing(
                                                fn($state, $record) =>
                                                TrainingApplication::where('training_id', $record->id)
                                                    ->where('centre', $state)
                                                    ->where('status', 'approved')
                                                    ->count()
                                            ),

                                        Infolists\Components\TextEntry::make('centre_id')
                                            ->label('Rejected')
                                            ->formatStateUsing(
                                                fn($state, $record) =>
                                                TrainingApplication::where('training_id', $record->id)
                                                    ->where('centre', $state)
                                                    ->where('status', 'rejected')
                                                    ->count()
                                            ),

                                        Infolists\Components\TextEntry::make('centre_id')
                                            ->label('Completed')
                                            ->formatStateUsing(
                                                fn($state, $record) =>
                                                TrainingApplication::where('training_id', $record->id)
                                                    ->where('centre', $state)
                                                    ->where('status', 'completed')
                                                    ->count()
                                            ),
                                    ])
                            ])
                    ])
            ]);
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