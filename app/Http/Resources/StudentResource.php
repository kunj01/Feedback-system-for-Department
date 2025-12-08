<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
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
            'user_id' => $this->user_id,
            'roll_no' => $this->roll_no,
            'registration_no' => $this->registration_no,
            'dob' => $this->dob?->format('Y-m-d'),
            'gender' => $this->gender,
            'father_name' => $this->father_name,
            'mother_name' => $this->mother_name,
            'address' => $this->address,
            'contact' => $this->contact,
            'email' => $this->email,
            'department_id' => $this->department_id,
            'course' => $this->course,
            'batch' => $this->batch,
            'cgpa' => $this->cgpa,
            'academic_details' => $this->academic_details,
            'training_status' => $this->training_status,
            'user' => new UserResource($this->whenLoaded('user')),
            'department' => new DepartmentResource($this->whenLoaded('department')),
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
