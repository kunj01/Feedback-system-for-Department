<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportLogRequest extends FormRequest
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
            'student_id' => ['required', 'exists:students,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'report_type' => ['required', 'in:WEEKLY,MONTHLY,FINAL,PROJECT,TRAINING,OTHER'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_path' => ['nullable', 'string'],
            'submitted_date' => ['required', 'date'],
            'status' => ['required', 'in:SUBMITTED,REVIEWED,APPROVED,REJECTED'],
            'reviewed_by' => ['nullable', 'exists:users,id'],
            'reviewed_at' => ['nullable', 'date'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
