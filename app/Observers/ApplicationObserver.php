<?php

namespace App\Observers;

use App\Models\TrainingApplication;
use Illuminate\Support\Facades\Mail;
use App\Mail\NomineeNotificationMail; 
use App\Mail\NominationStatusMail;
use App\Mail\FeedbackReminderMail;


class ApplicationObserver
{
    public function created(TrainingApplication $trainingApplication): void
    {
        if ($trainingApplication->nominee_email) {
            \Log::info('Sending mail to: ' . $trainingApplication->nominee_email);

            Mail::to($trainingApplication->nominee_email)->send(
                new NomineeNotificationMail($trainingApplication)
            );
        }
    }

    public function updated(TrainingApplication $trainingApplication)
    {
        if ($trainingApplication->isDirty('status')) {

            if ($trainingApplication->status === 'completed') {

                Mail::to($trainingApplication->nominee_email)->send(
                    new NominationStatusMail($trainingApplication, $trainingApplication->status)
                );
                
                Mail::to($trainingApplication->nominee_email)->send(
                    new FeedbackReminderMail($trainingApplication)
                );
                
            } else {

                Mail::to($trainingApplication->nominee_email)->send(
                    new NominationStatusMail($trainingApplication, $trainingApplication->status)
                );
            }
        }
    }
}