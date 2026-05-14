<?php

namespace App\Mail;

use App\Models\Workspace;
use App\Models\WorkspaceInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WorkspaceInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $workspace;
    public $invitation;
    public $acceptUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Workspace $workspace, WorkspaceInvitation $invitation)
    {
        $this->workspace = $workspace;
        $this->invitation = $invitation;
        $this->acceptUrl = url("/workspace/invitation/{$invitation->token}/accept");
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "You've been invited to join \"{$this->workspace->name}\" workspace",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.workspace-invitation',
        );
    }
}
