<?php

namespace App\Filament\Coordinator\Resources\TrainingApplicationResource\Pages;

use App\Filament\Coordinator\Resources\TrainingApplicationResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\TrainingApplication;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateTrainingApplication extends CreateRecord
{
    protected static string $resource = TrainingApplicationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $trainingId  = $data['training_id'];
        $centreId    = auth()->user()->centre_id;
        $nominations = collect($data['nominations'] ?? []);

        if ($nominations->isEmpty()) {
            Notification::make()->title('No nominees added')->danger()->send();
            $this->halt();
        }

        // Duplicate check
        $userIds = $nominations->pluck('user_id')->filter();

        if ($userIds->duplicates()->isNotEmpty()) {
            Notification::make()->title('Duplicate employee detected')->danger()->send();
            $this->halt();
        }

        // Already applied check
        $alreadyAppliedUsers = TrainingApplication::where('training_id', $trainingId)
            ->pluck('user_id');

        if ($userIds->intersect($alreadyAppliedUsers)->isNotEmpty()) {
            Notification::make()->title('Employee already applied for this training')->danger()->send();
            $this->halt();
        }

        // Training validation
        $training = \App\Models\Training::find($trainingId);

        if (!$training) {
            Notification::make()->title('Training not found')->danger()->send();
            $this->halt();
        }

        // Seat distribution
        $distribution = collect($training->seat_distribution ?? [])
            ->firstWhere('centre_id', $centreId);

        if (!$distribution) {
            Notification::make()->title('No seats allocated for your centre')->danger()->send();
            $this->halt();
        }

        $allottedSeats = (int) ($distribution['seats'] ?? 0);

        $alreadyApplied = TrainingApplication::where('training_id', $trainingId)
            ->where('centre', $centreId)
            ->count();

        if (($alreadyApplied + $nominations->count()) > $allottedSeats) {

            Notification::make()
                ->title('Seat limit exceeded')
                ->body("Only {$allottedSeats} seats allowed for your centre")
                ->danger()
                ->send();

            $this->halt();
        }

        // Create records
        $firstRecord = null;

        foreach ($nominations as $nominee) {

            $record = TrainingApplication::create([
                'training_id'         => $trainingId,
                'user_id'             => $nominee['user_id'],
                'nominee_emp_id'      => $nominee['nominee_emp_id'],
                'nominee_name'        => $nominee['nominee_name'],
                'nominee_designation' => $nominee['nominee_designation'],
                'nominee_email'       => $nominee['nominee_email'],
                'nominee_phone'       => $nominee['nominee_phone'],
                'is_self_apply'       => $data['is_self_apply'] ?? false,
                'status'              => 'submitted',
                'centre'              => $centreId,
            ]);

            $firstRecord ??= $record;
        }

        return $firstRecord;
    }

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        $trainingId = $data['training_id'];

        $nominations = $data['nominations'] ?? [];

        $stats = TrainingApplicationResource::seatStats($trainingId, count($nominations));

        if ($stats['remaining'] < 0) {

            Notification::make()
                ->title('Seat limit exceeded')
                ->danger()
                ->send();

            $this->halt();
        }
    }


    public function mount(): void
    {
        parent::mount();

        if ($trainingId = request()->query('training_id')) {
            $this->form->fill([
                'training_id' => (int) $trainingId,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}