<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'address' => $this->address,
            'contact_person' => $this->contact_person,
            'contact_email' => $this->contact_email,
            'website' => $this->website,
            'notes' => $this->notes,
            'projects_count' => $this->when($this->relationLoaded('projects'), function () {
                return $this->projects->count();
            }),
            'placements_count' => $this->when($this->relationLoaded('placements'), function () {
                return $this->placements->count();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
