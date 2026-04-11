<?php

namespace App\Filament\Coordinator\Resources\TrainingApplicationResource\Pages;

use App\Filament\Coordinator\Resources\TrainingApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTrainingApplications extends ListRecords
{
    protected static string $resource = TrainingApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()->label('New Training Request'),
        ];
    }

    public function getTabs(): array
    {
        return [



            'staff_nominations' => Tab::make('Employee')
                ->icon('heroicon-m-users')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->where('centre', auth()->user()->centre_id) 
                        ->where('user_id', '!=', auth()->id())      
                        ->whereHas('user', function ($q) {         
                            $q->where('role', 'employee');
                        });
                }),

            'my_applications' => Tab::make('My Applications')
                ->icon('heroicon-m-user')
                ->modifyQueryUsing(
                    fn(Builder $query) =>
                    $query->where('user_id', auth()->id())
                ),

            'all' => Tab::make('All Records')
                ->icon('heroicon-m-list-bullet'),
        ];
    }


}