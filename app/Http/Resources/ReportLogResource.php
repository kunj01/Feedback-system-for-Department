<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'student_name' => $this->whenLoaded('student', fn() => $this->student->user->name ?? null),
            'student_roll_no' => $this->whenLoaded('student', fn() => $this->student->roll_no ?? null),
            'project_id' => $this->project_id,
            'project_title' => $this->whenLoaded('project', fn() => $this->project->title ?? null),
            'report_type' => $this->report_type,
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $this->file_path,
            'file_url' => $this->file_path ? url('storage/' . $this->file_path) : null,
            'submitted_date' => $this->submitted_date?->format('Y-m-d'),
            'status' => $this->status,
            'reviewed_by' => $this->reviewed_by,
            'reviewer_name' => $this->whenLoaded('reviewer', fn() => $this->reviewer->name ?? null),
            'reviewed_at' => $this->reviewed_at?->format('Y-m-d H:i:s'),
            'remarks' => $this->remarks,
            'created_by' => $this->created_by,
            'creator_name' => $this->whenLoaded('creator', fn() => $this->creator->name ?? null),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
