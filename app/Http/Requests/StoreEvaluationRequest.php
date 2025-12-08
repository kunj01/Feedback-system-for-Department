<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationRequest extends FormRequest
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
            'project_id' => ['required', 'exists:projects,id'],
            'student_id' => ['required', 'exists:students,id'],
            'evaluator_id' => ['required', 'exists:users,id'],
            'evaluation_type' => ['required', 'in:INTERNAL,EXTERNAL,REPORT'],
            'evaluation_date' => ['required', 'date'],
            'marks_obtained' => ['required', 'numeric', 'min:0', 'max:100'],
            'total_marks' => ['required', 'numeric', 'min:0', 'max:100'],
            'grade' => ['nullable', 'string', 'max:5'],
            'feedback' => ['nullable', 'string'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
