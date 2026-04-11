<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrainingPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $training;
    public $allottedSeats;

    public function __construct($training, $allottedSeats)
    {
        $this->training = $training;
        $this->allottedSeats = $allottedSeats;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Training Notification: ' . $this->training->title,
        );
    }

    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.training-published', 
    //     );
    // }

    public function content(): Content
    {
        $tpl = \App\Models\EmailTemplate::first();
        $body = str_replace(
            ['{training_title}', '{allotted_seats}'],
            [$this->training->title, $this->allottedSeats],
            $tpl->pub_body
        );
        return new Content(
            view: 'emails.training-published',
            with: [
                'mailTitle'   => $tpl->pub_title,
                'headerColor' => $tpl->pub_color,
                'mailMessage' => $body,
                'training'    => $this->training,
                'allottedSeats' => $this->allottedSeats,
            ],
        );
    }
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}