<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AbsenceThresholdMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $recipientType;
    public string $recipientName;

    public function __construct(
        public Student $student,
        public int $totalAbsences,
        public float $totalHours,
        public int $unjustifiedCount,
        string $recipientType = 'admin',
        string $recipientName = 'Admin'
    ) {
        $this->recipientType = $recipientType;
        $this->recipientName = $recipientName;
    }

    public function envelope(): Envelope
    {
        $studentName = $this->student->user->first_name . ' ' . $this->student->user->last_name;

        return new Envelope(
            subject: "OFPPT - ⚠️ Alerte absences : {$studentName} ({$this->totalAbsences} absences)",
        );
    }

    public function content(): Content
    {
        $user = $this->student->user;
        $group = $this->student->group;

        return new Content(
            view: 'emails.absence-threshold',
            with: [
                'recipientName'   => $this->recipientName,
                'recipientType'   => $this->recipientType,
                'studentName'     => $user->first_name . ' ' . $user->last_name,
                'groupName'       => $group->name ?? 'N/A',
                'totalAbsences'   => $this->totalAbsences,
                'totalHours'      => $this->totalHours,
                'unjustifiedCount' => $this->unjustifiedCount,
                'appUrl'          => config('app.frontend_url', 'https://ofppt-futurelearn.vercel.app'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
