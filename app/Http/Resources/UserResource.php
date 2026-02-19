<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'profile_image' => $this->profile_image ? asset('storage/' . $this->profile_image) : null,
            'role'       => $this->role,
            'is_active'  => $this->is_active,
            'must_change_password' => $this->must_change_password,
        ];
    }
}
