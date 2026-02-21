<?php

namespace App\Mail;

use App\Models\Absence;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbsenceMarkedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Absence $absence)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'OFPPT - Nouvelle absence enregistrée',
        );
    }

    public function content(): Content
    {
        $student = $this->absence->student->user;
        $teacher = $this->absence->teacher->user;

        return new Content(
            view: 'emails.absence-marked',
            with: [
                'studentName' => $student->first_name . ' ' . $student->last_name,
                'teacherName' => $teacher->first_name . ' ' . $teacher->last_name,
                'date'        => $this->absence->date,
                'startTime'   => $this->absence->start_time,
                'endTime'     => $this->absence->end_time,
                'subject'     => $this->absence->subject,
                'appUrl'      => config('app.frontend_url', 'https://ofppt-futurelearn.vercel.app'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
