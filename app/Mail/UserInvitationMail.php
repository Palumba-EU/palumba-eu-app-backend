<?php

namespace App\Mail;

use App\Models\UserInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(private UserInvitation $invitation)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('user_invitation.mail.subject', [ 'app_name' => config('app.name') ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.user-invitation-mail',
            with: [
                'acceptUrl' => URL::signedRoute(
                    'filament.admin.auth.register',
                    [
                        'token' => $this->invitation->code,
                    ],
                ),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
