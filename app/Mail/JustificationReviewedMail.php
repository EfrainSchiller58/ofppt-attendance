<?php

namespace App\Mail;

use App\Models\Justification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JustificationReviewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Justification $justification, public string $decision)
    {
    }

    public function envelope(): Envelope
    {
        $label = $this->decision === 'approved' ? 'approuvée' : 'rejetée';
        return new Envelope(
            subject: "OFPPT - Justification {$label}",
        );
    }

    public function content(): Content
    {
        $student = $this->justification->absence->student->user;

        return new Content(
            markdown: 'emails.justification-reviewed',
            with: [
                'studentName' => $student->first_name . ' ' . $student->last_name,
                'decision'    => $this->decision,
                'reason'      => $this->justification->reason,
                'reviewNote'  => $this->justification->review_note,
                'date'        => $this->justification->absence->date,
                'subject'     => $this->justification->absence->subject,
                'appUrl'      => config('app.frontend_url', 'https://ofppt-futurelearn.vercel.app'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
