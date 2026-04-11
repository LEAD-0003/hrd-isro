<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NomineeNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    // Public variables-ai inga define pannunga
    public $application;
    public $training;
    public $headerColor;
    public $mailTitle;
    public $mailMessage;

    public function __construct($application)
    {
        $this->application = $application;
        // Application-la irundhu training model-ai edukkuroam
        $this->training = $application->training;

        // Template-kku vendiya default values
        $this->headerColor = '#1e293b';
        $this->mailTitle = 'Training Nomination Received';
        $this->mailMessage = "You have been nominated for the following training program by your centre coordinator.";
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Training Nomination: ' . ($this->training->title ?? 'Notification'),
        );
    }

    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.training-nominated', 
    //     );
    // }


    public function content(): Content
    {
        $tpl = \App\Models\EmailTemplate::first();
        $body = str_replace(
            ['{nominee_name}', '{training_title}'],
            [$this->application->nominee_name, $this->training->title],
            $tpl->nom_body
        );
        return new Content(
            view: 'emails.training-nominated',
            with: [
                'mailTitle'   => $tpl->nom_title,
                'headerColor' => $tpl->nom_color,
                'mailMessage' => $body,
                'training'    => $this->training,
            ],
        );
    }
}