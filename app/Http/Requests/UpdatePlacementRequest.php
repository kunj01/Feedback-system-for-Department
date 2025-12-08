<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['sometimes', 'exists:students,id'],
            'company_id' => ['sometimes', 'exists:companies,id'],
            'placement_type' => ['sometimes', 'in:INTERNSHIP,FULL_TIME,PART_TIME'],
            'job_title' => ['sometimes', 'string', 'max:255'],
            'offer_date' => ['sometimes', 'date'],
            'joining_date' => ['nullable', 'date', 'after_or_equal:offer_date'],
            'package_lpa' => ['sometimes', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_description' => ['nullable', 'string'],
            'offer_letter_path' => ['nullable', 'string'],
            'is_confirmed' => ['boolean'],
            'confirmed_date' => ['nullable', 'date', 'required_if:is_confirmed,true'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
