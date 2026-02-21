<?php

namespace App\Mail;

use App\Models\Justification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JustificationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Justification $justification)
    {
    }

    public function envelope(): Envelope
    {
        $student = $this->justification->absence->student->user;
        $name = $student->first_name . ' ' . $student->last_name;

        return new Envelope(
            subject: "OFPPT - Nouvelle justification de {$name}",
        );
    }

    public function content(): Content
    {
        $student = $this->justification->absence->student->user;
        $group = $this->justification->absence->group;

        return new Content(
            view: 'emails.justification-submitted',
            with: [
                'studentName' => $student->first_name . ' ' . $student->last_name,
                'groupName'   => $group->name ?? 'N/A',
                'date'        => $this->justification->absence->date,
                'subject'     => $this->justification->absence->subject,
                'reason'      => $this->justification->reason,
                'fileName'    => $this->justification->file_name,
                'appUrl'      => config('app.frontend_url', 'https://ofppt-futurelearn.vercel.app'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
