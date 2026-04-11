<?php

namespace App\Mail;

use App\Models\TrainingApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $training;
    public $status;
    public $mailTitle;
    public $mailMessage;
    public $headerColor;

    public function __construct(TrainingApplication $application, $status)
    {
        $this->application = $application;
        $this->training = $application->training;
        $this->status = $status;

        switch ($status) {

            case 'approved':
                $this->mailTitle = 'Training Application Approved';
                $this->headerColor = '#16a34a';
                $this->mailMessage = 'Your training application has been <b>approved</b>.';
                break;

            case 'rejected':
                $this->mailTitle = 'Training Application Rejected';
                $this->headerColor = '#dc2626';
                $this->mailMessage = 'Your training application has been <b>rejected</b>.';
                break;

            case 'completed':
                $this->mailTitle = 'Training Completed';
                $this->headerColor = '#2563eb';
                $this->mailMessage = 'The training program has been successfully <b>completed</b>.';
                break;

            case 'notification':
                $this->mailTitle = 'New Training Notification';
                $this->headerColor = '#1e3a8a';
                $this->mailMessage = 'A new training program has been scheduled.';
                break;

            default:
                $this->mailTitle = 'Training Update';
                $this->headerColor = '#475569';
                $this->mailMessage = 'Your training application status has been updated.';
        }
    }

    public function build()
    {
        return $this->subject($this->mailTitle)
            ->view('emails.training-approved'); 
    }
}