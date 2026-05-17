<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HelpRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $problem;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $email, $problem)
    {
        $this->name = $name;
        $this->email = $email;
        $this->problem = $problem;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Help Request from ' . $this->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.help_request',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'problem' => $this->problem,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
