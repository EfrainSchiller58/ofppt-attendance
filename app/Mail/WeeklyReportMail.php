<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $teacherName,
        public string $weekStart,
        public string $weekEnd,
        public int $totalAbsences,
        public int $justifiedCount,
        public int $unjustifiedCount,
        public array $groups,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "OFPPT - Rapport hebdomadaire ({$this->weekStart} - {$this->weekEnd})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-report',
            with: [
                'teacherName'     => $this->teacherName,
                'weekStart'       => $this->weekStart,
                'weekEnd'         => $this->weekEnd,
                'totalAbsences'   => $this->totalAbsences,
                'justifiedCount'  => $this->justifiedCount,
                'unjustifiedCount' => $this->unjustifiedCount,
                'groups'          => $this->groups,
                'appUrl'          => config('app.frontend_url', 'https://ofppt-futurelearn.vercel.app'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
