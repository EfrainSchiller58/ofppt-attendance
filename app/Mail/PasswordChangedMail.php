<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $ipAddress,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OFPPT - Mot de passe modifié 🔒',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-changed',
            with: [
                'userName'  => $this->user->first_name . ' ' . $this->user->last_name,
                'email'     => $this->user->email,
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
