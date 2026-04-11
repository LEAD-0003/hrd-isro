<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NominationStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $status;

    public function __construct($application, $status)
    {
        $this->application = $application;
        $this->status = $status;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Training Nomination Status: ' . ucfirst($this->status),
        );
    }

    public function content(): Content
    {
        $headerColor = $this->status === 'approved' ? '#16a34a' : '#ef4444'; 
        $mailTitle = 'Application ' . ucfirst($this->status);
        $mailMessage = 'Your application for the following training program has been <strong>' . $this->status . '</strong>.';

        return new Content(
            view: 'emails.training-approved',
            with: [
                'headerColor' => $headerColor,
                'mailTitle'   => $mailTitle,
                'mailMessage' => $mailMessage,
                'training'    => $this->application->training, 
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}