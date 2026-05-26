<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $emailAddress;
    public $reason;
    public $description;

    public function __construct($emailAddress, $reason, $description)
    {
        $this->emailAddress = $emailAddress;
        $this->reason = $reason;
        $this->description = $description;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'TechBuild - Password Restoration Request for ' . $this->emailAddress,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.forgot-password-request',
        );
    }
}
