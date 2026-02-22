<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $oldEmail,
        public string $newEmail,
        public string $ipAddress,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OFPPT - Adresse email modifiée 📧',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.email-changed',
            with: [
                'userName'  => $this->user->first_name . ' ' . $this->user->last_name,
                'oldEmail'  => $this->oldEmail,
                'newEmail'  => $this->newEmail,
                'changedAt' => now()->format('d/m/Y à H:i'),
                'ipAddress' => $this->ipAddress,
                'appUrl'    => config('app.frontend_url', 'https://ofppt-futurelearn.vercel.app'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
