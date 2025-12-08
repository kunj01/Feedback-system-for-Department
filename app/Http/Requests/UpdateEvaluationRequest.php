<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['sometimes', 'exists:projects,id'],
            'student_id' => ['sometimes', 'exists:students,id'],
            'evaluator_id' => ['sometimes', 'exists:users,id'],
            'evaluation_type' => ['sometimes', 'in:INTERNAL,EXTERNAL,REPORT'],
            'evaluation_date' => ['sometimes', 'date'],
            'marks_obtained' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'total_marks' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'grade' => ['nullable', 'string', 'max:5'],
            'feedback' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
