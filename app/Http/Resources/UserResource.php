<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'current_address' => $this->current_address,
            'permanent_address' => $this->permanent_address,
            'highest_level_qualification' => $this->highest_level_qualification,
            'highest_level_qualification_faculty' => $this->highest_level_qualification_faculty,
            'service_perferences' => $this->preferences,
        ];
    }
}
