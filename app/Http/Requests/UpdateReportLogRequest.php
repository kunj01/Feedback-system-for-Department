<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['sometimes', 'exists:students,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'report_type' => ['sometimes', 'in:WEEKLY,MONTHLY,FINAL,PROJECT,TRAINING,OTHER'],
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string'],
            'submitted_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'in:SUBMITTED,REVIEWED,APPROVED,REJECTED'],
            'reviewed_by' => ['nullable', 'exists:users,id'],
            'reviewed_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
