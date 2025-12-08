<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
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
            'project_title' => $this->whenLoaded('project', fn() => $this->project->title ?? null),
            'student_id' => $this->student_id,
            'student_name' => $this->whenLoaded('student', fn() => $this->student->user->name ?? null),
            'student_roll_no' => $this->whenLoaded('student', fn() => $this->student->roll_no ?? null),
            'evaluator' => new UserResource($this->whenLoaded('evaluator')),
            'evaluation_type' => $this->evaluation_type,
            'evaluation_date' => $this->evaluation_date?->format('Y-m-d'),
            'marks_obtained' => $this->marks_obtained,
            'total_marks' => $this->total_marks,
            'percentage' => $this->total_marks > 0 ? round(($this->marks_obtained / $this->total_marks) * 100, 2) : 0,
            'grade' => $this->grade,
            'feedback' => $this->feedback,
            'remarks' => $this->remarks,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
