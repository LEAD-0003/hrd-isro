<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\TrainingStatusResource\Pages;
use App\Models\TrainingApplication;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class TrainingStatusResource extends Resource
{
    protected static ?string $model = TrainingApplication::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationLabel = 'Training History';
    protected static ?string $breadcrumb = 'Training History';
    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) =>
            $query->where('user_id', auth()->id())
                ->with(['training', 'training.trainingType'])
                ->orderBy('id', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('training.title')
                    ->label('Training Name')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('training.start_date')
                    ->label('Date')
                    ->date('d/m/Y'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->color(fn(string $state): string => match ($state) {
                        'submitted' => 'warning',
                        'recommended' => 'info',
                        'waiting' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'primary',
                        default => 'gray',
                    }),

    
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Details')
                    ->modalHeading('Training Information')
                    ->infolist(
                        fn(Infolist $infolist): Infolist => $infolist
                            ->schema([
                                Infolists\Components\Section::make('General Information')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('training.title')
                                            ->label('Training Name')
                                            ->weight('bold'),

                                        Infolists\Components\TextEntry::make('training.trainingType.name')
                                            ->label('Type')
                                            ->badge(),

                                        Infolists\Components\TextEntry::make('status')
                                            ->label('Status')
                                            ->badge()
                                            ->formatStateUsing(fn(string $state): string => ucfirst($state))
                                            ->color(fn(string $state): string => match ($state) {
                                                'submitted' => 'warning',
                                                'recommended' => 'info',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'completed' => 'primary',
                                                default => 'gray',
                                            }),

                                        Infolists\Components\TextEntry::make('training.start_date')
                                            ->label('Start Date')
                                            ->date('d/m/Y'),

                                        Infolists\Components\TextEntry::make('training.end_date')
                                            ->label('End Date')
                                            ->date('d/m/Y'),

                        Infolists\Components\RepeatableEntry::make('attachments')
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

                                    ])
                                    ->columns(3),
                            ])
                    ),

                Tables\Actions\Action::make('feedback')
                    ->label('Provide Feedback')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('warning')
                    ->visible(fn($record) => $record->status === 'completed' && !$record->feedback()->exists())
                    ->form([
                        Forms\Components\Wizard::make([
                            Forms\Components\Wizard\Step::make('Content & Relevance')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('clarity_objectives')->label('Clarity of objectives')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('relevance_to_role')->label('Relevance to your role')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('quality_materials')->label('Quality of materials/examples')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('practical_applicability')->label('Practical applicability')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                    ]),
                                ]),

                            Forms\Components\Wizard\Step::make('Trainer Effectiveness')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('subject_knowledge')->label('Subject knowledge')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('clarity_communication')->label('Clarity & communication')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('engagement_interaction')->label('Engagement & interaction')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('time_management')->label('Time management')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                    ]),
                                ]),

                            Forms\Components\Wizard\Step::make('Logistics')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('venue_platform_quality')->label('Venue / Platform quality')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('organization_coordination')->label('Organization & coordination')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('accommodation_boarding')->label('Accommodation & Boarding')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('transportation')->label('Transportation')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                    ]),
                                ]),

                            Forms\Components\Wizard\Step::make('Overall Assessment')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('met_expectations')->label('Programme met expectations')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                        Forms\Components\Select::make('overall_rating')->label('Overall Rating (5-Excellent | 1-Poor)')->options([5 => 5, 4 => 4, 3 => 3, 2 => 2, 1 => 1])->required(),
                                    ]),
                                    Forms\Components\Textarea::make('most_useful_aspect')->label('Most Useful Aspect')->required(),
                                    Forms\Components\Textarea::make('areas_for_improvement')->label('Areas for Improvement')->required(),
                                ]),
                        ]),
                    ])
                    ->action(function (TrainingApplication $record, array $data) {
                        $record->feedback()->create($data);
                        $record->update(['feedback_rating' => $data['overall_rating']]);
                    }),

                Tables\Actions\Action::make('view_feedback')
                    ->label('View Feedback')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->visible(fn(TrainingApplication $record) => $record->feedback()->exists())
                    ->modalHeading('Detailed Training Feedback')
                    ->modalSubmitAction(false)
                    ->infolist(
                        fn(Infolist $infolist): Infolist => $infolist
                            ->schema([
                                Infolists\Components\Grid::make(1)
                                    ->schema([
                                        Infolists\Components\ViewEntry::make('feedback_table')
                                            ->view('filament.infolists.feedback-table')
                                            ->columnSpanFull(),
                                    ]),

                                Infolists\Components\Section::make('Overall Assessment')
                                    ->schema([
                                        Infolists\Components\Grid::make(2)
                                            ->schema([
                                                Infolists\Components\TextEntry::make('feedback.met_expectations')->label('Programme met expectations'),
                                                Infolists\Components\TextEntry::make('feedback.overall_rating')->label('Overall Rating')->badge()->color('warning'),
                                            ]),

                                        Infolists\Components\TextEntry::make('feedback.most_useful_aspect')
                                            ->label('Most Useful Aspect:')
                                            ->html()
                                            ->formatStateUsing(fn($state) => "
                                                <div class='relative bg-blue-50/50 dark:bg-blue-900/20 p-4 rounded-sm min-h-[80px] overflow-hidden' style='margin-top: 8px;'>
                                                    <p class='text-gray-800 dark:text-gray-200 leading-[40px] relative z-10' style='margin: 0; padding-top: 4px;'>
                                                        " . ($state ?? 'N/A') . "
                                                    </p>
                                                
                                                </div>
                                            ")
                                            ->columnSpanFull(),

                                        Infolists\Components\TextEntry::make('feedback.areas_for_improvement')
                                            ->label('Areas for Improvement:')
                                            ->html()
                                            ->formatStateUsing(fn($state) => "
                                                    <div class='relative bg-blue-50/50 dark:bg-blue-900/20 p-4 rounded-sm min-h-[80px] overflow-hidden' style='margin-top: 8px;'>
                                                        <p class='text-gray-800 dark:text-gray-200 leading-[40px] relative z-10' style='margin: 0; padding-top: 4px;'>
                                                            " . ($state ?? 'N/A') . "
                                                        </p>
                                                        
                                                    </div>
                                                ")
                                            ->columnSpanFull(),
                                    ]),
                            ])
                    ),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn(TrainingApplication $record) => $record->status === 'submitted'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingStatuses::route('/'),
        ];
    }
}