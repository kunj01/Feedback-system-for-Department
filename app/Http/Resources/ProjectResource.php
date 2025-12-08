<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'project_id' => $this->project_id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'company_id' => $this->company_id,
            'guide_id' => $this->guide_id,
            'co_guide_ids' => $this->co_guide_ids,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date' => $this->end_date?->format('Y-m-d'),
            'status' => $this->status,
            'is_group' => $this->is_group,
            'max_group_size' => $this->max_group_size,
            'created_by' => $this->created_by,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'guide' => new UserResource($this->whenLoaded('guide')),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'students' => StudentResource::collection($this->whenLoaded('students')),
            'students_count' => $this->when($this->relationLoaded('students'), function () {
                return $this->students->count();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
