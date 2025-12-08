<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlacementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_name' => $this->whenLoaded('student', fn() => $this->student->user->name ?? null),
            'student_roll_no' => $this->whenLoaded('student', fn() => $this->student->roll_no ?? null),
            'company' => new CompanyResource($this->whenLoaded('company')),
            'placement_type' => $this->placement_type,
            'job_title' => $this->job_title,
            'offer_date' => $this->offer_date?->format('Y-m-d'),
            'joining_date' => $this->joining_date?->format('Y-m-d'),
            'package_lpa' => $this->package_lpa,
            'location' => $this->location,
            'job_description' => $this->job_description,
            'offer_letter_path' => $this->offer_letter_path,
            'is_confirmed' => $this->is_confirmed,
            'confirmed_date' => $this->confirmed_date?->format('Y-m-d'),
            'remarks' => $this->remarks,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
