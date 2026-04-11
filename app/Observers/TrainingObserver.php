<?php

namespace App\Observers;

use App\Models\Training;
use App\Models\User;

use App\Mail\TrainingPublishedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
class TrainingObserver
{
    /**
     * Handle the Training "created" event.
     */
    public function created(Training $training): void
    {
        \Log::info('Training Observer Triggered');

        $coordinators = User::where('role', 'coordinator')->get();

        foreach ($coordinators as $user) {

            $allottedSeats = 0;

            foreach ($training->seat_distribution ?? [] as $seat) {
                if ($seat['centre_id'] == $user->centre_id) {
                    $allottedSeats = $seat['seats'];
                    break;
                }
            }

            if ($user->email && $allottedSeats > 0) {
                Mail::to($user->email)->send(
                    new TrainingPublishedMail($training, $allottedSeats)
                );
            }
        }
    }
    /**
     * Handle the Training "updated" event.
     */
    public function updated(Training $training): void
    {
        \Log::info('Training Observer Triggered');

        $coordinators = User::where('role', 'coordinator')->get();

        foreach ($coordinators as $user) {

            $allottedSeats = 0;

            foreach ($training->seat_distribution ?? [] as $seat) {
                if ($seat['centre_id'] == $user->centre_id) {
                    $allottedSeats = $seat['seats'];
                    break;
                }
            }

            if ($user->email && $allottedSeats > 0) {
                Mail::to($user->email)->send(
                    new TrainingPublishedMail($training, $allottedSeats)
                );
            }
        }
    }

    /**
     * Handle the Training "deleted" event.
     */
    public function deleted(Training $training): void
    {
        //
    }

    /**
     * Handle the Training "restored" event.
     */
    public function restored(Training $training): void
    {
        //
    }

    /**
     * Handle the Training "force deleted" event.
     */
    public function forceDeleted(Training $training): void
    {
        //
    }
}
