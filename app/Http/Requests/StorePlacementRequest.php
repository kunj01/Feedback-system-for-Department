<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'company_id' => ['required', 'exists:companies,id'],
            'placement_type' => ['required', 'in:INTERNSHIP,FULL_TIME,PART_TIME'],
            'job_title' => ['required', 'string', 'max:255'],
            'offer_date' => ['required', 'date'],
            'joining_date' => ['nullable', 'date', 'after_or_equal:offer_date'],
            'package_lpa' => ['required', 'numeric', 'min:0'],
            'location' => ['nullable', 'string', 'max:255'],
            'job_description' => ['nullable', 'string'],
            'offer_letter_path' => ['nullable', 'string'],
            'is_confirmed' => ['boolean'],
            'confirmed_date' => ['nullable', 'date', 'required_if:is_confirmed,true'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
