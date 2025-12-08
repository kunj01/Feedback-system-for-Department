<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'sometimes|required|in:COMPANY_PROJECT,IN_HOUSE',
            'company_id' => 'nullable|exists:companies,id',
            'guide_id' => 'nullable|exists:users,id',
            'co_guide_ids' => 'nullable|array',
            'co_guide_ids.*' => 'exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'nullable|in:OPEN,IN_PROGRESS,COMPLETED,CANCELLED',
            'is_group' => 'nullable|boolean',
            'max_group_size' => 'nullable|integer|min:1',
        ];
    }
}
