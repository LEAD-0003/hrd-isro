<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    // Inga variables-ai define panroam
    public function __construct(
        public $url,
        public $mailTitle = 'Password Reset Request',
        public $headerColor = '#0a192f' // ISRO Blue color
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset - ISRO HRD',
        );
    }

    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'emails.password-reset', 
    //         with: [
    //             'resetUrl' => $this->url,
    //             'mailTitle' => $this->mailTitle,
    //             'headerColor' => $this->headerColor,
    //             'mailMessage' => 'We received a request to reset your password for the ISRO HRD portal.',
    //         ],
    //     );
    // }

    public function content(): Content
    {
        $tpl = \App\Models\EmailTemplate::first();
        return new Content(
            view: 'emails.password-reset',
            with: [
                'resetUrl'    => $this->url,
                'mailTitle'   => $tpl->reset_title,
                'headerColor' => $tpl->reset_color,
                'mailMessage' => $tpl->reset_body,
            ],
        );
    }
}