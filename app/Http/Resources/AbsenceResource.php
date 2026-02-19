<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AbsenceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'student_id'   => $this->student_id,
            'student_name' => $this->student->user->first_name . ' ' . $this->student->user->last_name,
            'group_name'   => $this->group->name,
            'date'         => $this->date,
            'start_time'   => $this->start_time,
            'end_time'     => $this->end_time,
            'hours'        => (float) $this->hours,
            'subject'      => $this->subject,
            'teacher_name' => $this->teacher->user->first_name . ' ' . $this->teacher->user->last_name,
            'notes'        => $this->notes ?? '',
            'status'       => $this->status,
        ];
    }
}
