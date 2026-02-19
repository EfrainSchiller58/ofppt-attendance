<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                   => $this->id,
            'user_id'              => $this->user_id,
            'first_name'           => $this->user->first_name,
            'last_name'            => $this->user->last_name,
            'email'                => $this->user->email,
            'cne'                  => $this->cne,
            'phone'                => $this->phone ?? '',
            'group_id'             => $this->group_id,
            'group_name'           => $this->group->name,
            'total_absence_hours'  => (float) $this->absences()->sum('hours'),
        ];
    }
}
