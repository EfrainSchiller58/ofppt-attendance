<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'level'         => $this->level,
            'student_count' => $this->students_count ?? $this->students()->count(),
            'teacher_count' => $this->teachers_count ?? $this->teachers()->count(),
        ];
    }
}
