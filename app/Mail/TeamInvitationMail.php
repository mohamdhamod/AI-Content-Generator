<?php

namespace App\Mail;

use App\Models\TeamWorkspace;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeamInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public TeamWorkspace $workspace;
    public User $inviter;
    public string $inviteeEmail;
    public string $role;
    public bool $isNewUser;
    public ?string $invitationToken;

    /**
     * Create a new message instance.
     */
    public function __construct(
        TeamWorkspace $workspace,
        User $inviter,
        string $inviteeEmail,
        string $role,
        bool $isNewUser = false,
        ?string $invitationToken = null
    ) {
        $this->workspace = $workspace;
        $this->inviter = $inviter;
        $this->inviteeEmail = $inviteeEmail;
        $this->role = $role;
        $this->isNewUser = $isNewUser;
        $this->invitationToken = $invitationToken;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->isNewUser 
            ? "You're invited to join {$this->workspace->name} on MedContent AI"
            : "Team Invitation: Join {$this->workspace->name}";
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.team-invitation',
            with: [
                'workspace' => $this->workspace,
                'inviter' => $this->inviter,
                'inviteeEmail' => $this->inviteeEmail,
                'role' => $this->role,
                'isNewUser' => $this->isNewUser,
                'invitationToken' => $this->invitationToken,
                'acceptUrl' => $this->getAcceptUrl(),
                'registerUrl' => $this->getRegisterUrl(),
            ],
        );
    }

    /**
     * Get accept invitation URL
     */
    protected function getAcceptUrl(): string
    {
        $locale = app()->getLocale();
        
        if ($this->isNewUser && $this->invitationToken) {
            return url("/{$locale}/register?invitation={$this->invitationToken}");
        }
        
        return url("/{$locale}/teams/invitations/accept?token={$this->invitationToken}");
    }

    /**
     * Get register URL for new users
     */
    protected function getRegisterUrl(): string
    {
        $locale = app()->getLocale();
        return url("/{$locale}/register?invitation={$this->invitationToken}&email={$this->inviteeEmail}");
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
