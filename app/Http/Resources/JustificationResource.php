<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JustificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'absence_id'   => $this->absence_id,
            'student_name' => $this->absence->student->user->first_name . ' ' . $this->absence->student->user->last_name,
            'date'         => $this->absence->date,
            'hours'        => (float) $this->absence->hours,
            'reason'       => $this->reason,
            'file_name'    => $this->file_name,
            'file_path'    => $this->file_path ? asset('storage/' . $this->file_path) : null,
            'file_type'    => $this->file_type,
            'submitted_at' => $this->submitted_at?->toISOString(),
            'status'       => $this->status,
            'review_note'  => $this->review_note,
        ];
    }
}
