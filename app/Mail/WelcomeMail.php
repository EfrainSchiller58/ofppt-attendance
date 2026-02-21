<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $temporaryPassword,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OFPPT Attendance - Bienvenue ! 🎓',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: [
                'userName'          => $this->user->first_name . ' ' . $this->user->last_name,
                'email'             => $this->user->email,
                'role'              => $this->user->role,
                'temporaryPassword' => $this->temporaryPassword,
                'appUrl'            => config('app.frontend_url', 'https://ofppt-futurelearn.vercel.app'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
