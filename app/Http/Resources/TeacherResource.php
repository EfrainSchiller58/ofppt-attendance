<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'first_name'      => $this->user->first_name,
            'last_name'       => $this->user->last_name,
            'email'           => $this->user->email,
            'phone'           => $this->user->phone,
            'subject'         => $this->subject,
            'groups_assigned' => $this->groups->pluck('name')->toArray(),
        ];
    }
}
