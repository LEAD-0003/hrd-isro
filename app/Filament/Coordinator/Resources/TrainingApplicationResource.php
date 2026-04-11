<?php

namespace App\Filament\Coordinator\Resources;

use App\Filament\Coordinator\Resources\TrainingApplicationResource\Pages;
use App\Models\TrainingApplication;
use App\Models\User;
use App\Models\Training;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Infolist;


class TrainingApplicationResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Training Requests';
    protected static ?string $breadcrumb = 'Training Requests';


    public static function seatStats($trainingId, $currentCount = 0): array
    {
        $centreId = auth()->user()->centre_id;

        $training = Training::find($trainingId);

        if (!$training) {
            return [
                'max' => 0,
                'filled' => 0,
                'remaining' => 0,
            ];
        }

        $dist = collect($training->seat_distribution ?? [])
            ->firstWhere('centre_id', $centreId);

        $max = (int) ($dist['seats'] ?? 0);

        $already = TrainingApplication::where('training_id', $trainingId)
            ->where('centre', $centreId)
            ->count();

        return [
            'max' => $max,
            'filled' => $already,
            'remaining' => $max - ($already + $currentCount),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('Step 1: Training Selection')
                ->schema([

                    Forms\Components\Select::make('training_id')
                        ->label('Select Training')
                        ->options(function ($record) {

                            if ($record) {
                                return Training::where('id', $record->training_id)
                                    ->pluck('title', 'id');
                            }

                            return Training::where('last_date_to_apply', '>=', now())
                                ->pluck('title', 'id');
                        })
                        ->required()
                        ->searchable()
                        ->reactive()
                        ->disabled()
                        ->dehydrated()
                        ->afterStateUpdated(function ($set) {

                            $set('nominations', []);
                            $set('is_self_apply', false);
                        })
                        ->columnSpanFull(),

                ]),

            Forms\Components\Section::make('Step 2: Nominee Management')
                ->schema([

                    Forms\Components\Placeholder::make('seat_summary')
                        ->label('Seat Status')
                        ->visible(fn(Forms\Get $get) => $get('training_id'))
                        ->content(function (Forms\Get $get) {

                            $trainingId = $get('training_id');

                            $current = count($get('nominations') ?? []);

                            $stats = self::seatStats($trainingId, $current);

                            if ($stats['remaining'] < 0) {

                                return new \Illuminate\Support\HtmlString(
                                    "<span class='text-danger-600 font-bold'>
                                ❌ Seat limit exceeded ({$stats['filled']} / {$stats['max']})
                                </span>"
                                );
                            }

                            return new \Illuminate\Support\HtmlString(
                                "<span class='text-success-600 font-bold'>
                            Seats Used: {$stats['filled']} / {$stats['max']}
                            | Remaining: {$stats['remaining']}
                            </span>"
                            );
                        }),

                Forms\Components\Toggle::make('is_self_apply')
                    ->label('self')
                    ->reactive()
                    ->disabled(function (Forms\Get $get) {
                        $trainingId = $get('training_id');
                        if (!$trainingId) return false;

                        return TrainingApplication::where('training_id', $trainingId)
                            ->where('user_id', auth()->id())
                            ->exists();
                    })
                    ->helperText(function (Forms\Get $get) {
                        $trainingId = $get('training_id');
                        $alreadyApplied = $trainingId && TrainingApplication::where('training_id', $trainingId)
                            ->where('user_id', auth()->id())
                            ->exists();

                        return $alreadyApplied ? 'You have already applied for this training.' : null;
                    })
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                        if (!$state) {
                            $set('nominations', collect($get('nominations'))
                                ->filter(fn($n) => $n['user_id'] != auth()->id())
                                ->values()
                                ->toArray());
                            return;
                        }

                        $user = auth()->user();
                        $nominations = $get('nominations') ?? [];

                        foreach ($nominations as $n) {
                            if ($n['user_id'] == $user->id) {
                                return;
                            }
                        }

                        $nominations[] = [
                            'user_id' => $user->id,
                            'nominee_emp_id' => $user->employee_code,
                            'nominee_name' => $user->name,
                            'nominee_designation' => $user->designation?->name,
                            'nominee_email' => $user->email,
                            'nominee_phone' => $user->phone,
                        ];

                        $set('nominations', $nominations);
                    }),

                    Forms\Components\Repeater::make('nominations')
                        ->label('Nominee List')
                        ->columnSpanFull()
                        ->defaultItems(0)
                        ->deletable()
                        ->reorderable(false)

                        ->addable(function (Forms\Get $get) {

                            $trainingId = $get('training_id');

                            if (!$trainingId) {
                                return false;
                            }

                            $current = count($get('nominations') ?? []);

                            $stats = self::seatStats($trainingId, $current);

                            return $stats['remaining'] > 0;
                        })

                        ->schema([

                            Forms\Components\Grid::make(3)
                                ->schema([

                                    Forms\Components\Select::make('user_id')
                                        ->label('Select Employee')
                                        ->required()
                                        ->searchable()
                                        ->reactive()

                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems()

                                        ->options(function (Forms\Get $get) {

                                            $centreId = auth()->user()->centre_id;

                                            $trainingId = $get('../../training_id');

                                            if (!$trainingId) {
                                                return [];
                                            }

                                            $alreadyApplied = TrainingApplication::where('training_id', $trainingId)
                                                ->pluck('user_id');

                                            return User::where('centre_id', $centreId)
                                                ->where('role', 'employee')
                                                ->whereNotIn('id', $alreadyApplied)
                                                ->pluck('name', 'id');
                                        })

                                        ->afterStateUpdated(function ($state, Forms\Set $set) {

                                            if (!$state) return;

                                            $user = User::with('designation')->find($state);

                                            if (!$user) return;

                                            $set('nominee_emp_id', $user->employee_code);
                                            $set('nominee_name', $user->name);
                                            $set('nominee_designation', $user->designation?->name);
                                            $set('nominee_email', $user->email);
                                            $set('nominee_phone', $user->phone);
                                        }),

                                    Forms\Components\TextInput::make('nominee_emp_id')
                                        ->label('Employee ID')
                                        ->readOnly(),

                                    Forms\Components\TextInput::make('nominee_name')
                                        ->label('Name')
                                        ->readOnly(),

                                ]),

                            Forms\Components\Grid::make(3)
                                ->schema([

                                    Forms\Components\TextInput::make('nominee_designation')
                                        ->required(),

                                    Forms\Components\TextInput::make('nominee_email')
                                        ->required()
                                        ->email(),

                                    Forms\Components\TextInput::make('nominee_phone')
                                        ->required(),

                                ]),

                        ]),

                ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal & Contact Info')
                    ->schema([
                        TextEntry::make('user.prefix')->label('Prefix'),
                        TextEntry::make('user.name')->label('Full Name'),
                        TextEntry::make('user.gender')->label('Gender'),
                        TextEntry::make('user.dob')->label('Date of Birth')->date(),
                        TextEntry::make('user.phone')->label('Mobile'),
                        TextEntry::make('user.landline')->label('Landline')->placeholder('N/A'),
                        TextEntry::make('user.email')->label('Official Email'),
                        TextEntry::make('user.employee_code')->label('Employee Code'),
                        TextEntry::make('user.designation.name')->label('Designation'),
                        TextEntry::make('centreRel.name')->label('Centre'),
                    ])->columns(5),

                Section::make('Current Training Request')
                    ->schema([
                        TextEntry::make('training.title')->label('Training Name')->weight('bold'),
                        TextEntry::make('nominee_name')->label('Participant Name'),
                        TextEntry::make('status')->badge()
                            ->color(fn($state) => match ($state) {
                                'submitted' => 'warning',
                                'approved' => 'success',
                                'completed' => 'primary',
                                'rejected' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('training.start_date')->label('Scheduled Date')->date(),
                RepeatableEntry::make('attachments')
                    ->label('Attached Documents')
                    ->visible(fn($record) => $record->status === 'approved' || $record->status === 'completed')
                    ->getStateUsing(function ($record) {

                        $attachments = $record->training?->attachments;

                        $files = is_string($attachments) ? json_decode($attachments, true) : $attachments;

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
                        TextEntry::make('file_path')
                            ->hiddenLabel()
                            ->formatStateUsing(fn($state) => basename($state))
                            ->icon('heroicon-m-arrow-down-tray')
                            ->color('primary')
                            ->badge()
                            ->url(fn($state) => asset('storage/' . $state))
                    ])
                    ->grid(2)
                    ->columnSpanFull(),
                TextEntry::make('hq_comments')->label('HQ Remarks')->placeholder('No comments yet.')->color('info'),

                        Section::make('Participant Feedback Details')
                            ->schema([
                                Grid::make(2)->schema([
                                    TextEntry::make('feedback.overall_rating')->label('Rating')->badge()->color('warning')->icon('heroicon-m-star'),
                                    TextEntry::make('feedback.met_expectations')->label('Met Expectations'),
                                ]),
                                TextEntry::make('feedback.most_useful_aspect')->label('Most Useful Aspect:')->prose(),
                                TextEntry::make('feedback.areas_for_improvement')->label('Areas for Improvement:')->prose(),
                            ])
                            ->compact()->collapsible()->collapsed()
                            ->visible(fn($record) => $record->feedback()->exists())
                    ])->columns(5),

                Section::make('Participant Training History')
                    ->schema([
                        RepeatableEntry::make('training_history')
                            ->label('')
                            ->state(function ($record) {
                                if (!$record->user_id) return [];
                                return TrainingApplication::where('user_id', $record->user_id)
                                    ->where('id', '!=', $record->id)
                                    ->with(['training', 'feedback'])->get()->toArray();
                            })
                            ->schema([
                                Grid::make(4)->schema([
                                    TextEntry::make('training.title')->label('Training'),
                                    TextEntry::make('training.start_date')->label('Date')->date(),
                                    TextEntry::make('status')->badge(),
                                    TextEntry::make('hq_comments')->label('HQ Remarks')->placeholder('No remarks')
                                ]),


                            ])
                            ->columns(1)->placeholder('No previous training history found.'),
                    ])
            ]);
    }



    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        return parent::getEloquentQuery()
            ->where(function (Builder $query) use ($user) {
                $query->where('centre', $user->centre_id)
                    ->orWhere('user_id', $user->id);
            })
            ->with(['training', 'user', 'user.designation', 'centreRel']);
    }



    public static function table(Table $table): Table

    {

        return $table

            ->columns([


                Tables\Columns\TextColumn::make('training_display')

                    ->label('Training Program')

                    ->getStateUsing(

                        fn($record) => ($record->training?->type ? "({$record->training->type}) " : "") . $record->training?->title

                    ),




                Tables\Columns\TextColumn::make('nominee_name')

                    ->label('Nominee Name')

                    ->searchable(),




                Tables\Columns\TextColumn::make('status')

                    ->badge()

                    ->color(fn($state): string => match ($state) {

                        'submitted' => 'warning',

                        'approved' => 'success',

                        'rejected' => 'danger',

                        default => 'gray',
                    }),




                Tables\Columns\TextColumn::make('centreRel.name')

                    ->label('Centre')

                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('training_id')

                    ->label('Filter by Training')

                    ->relationship('training', 'title')

                    ->searchable()

                    ->preload(),

            ])

            ->actions([

                Tables\Actions\ViewAction::make(),

                Tables\Actions\EditAction::make()

                    ->visible(fn($record) => $record->status === 'submitted'),

            ])

            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([

                    Tables\Actions\DeleteBulkAction::make(),

                ]),

            ]);
    }

    public static function getPages(): array

    {

        return [

            'index' => Pages\ListTrainingApplications::route('/'),

            'create' => Pages\CreateTrainingApplication::route('/create'),

            'edit' => Pages\EditTrainingApplication::route('/{record}/edit'),

        ];
    }
}