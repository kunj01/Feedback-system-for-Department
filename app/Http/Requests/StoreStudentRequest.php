<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'user_id' => 'nullable|exists:users,id',
            'roll_no' => 'nullable|string|max:50',
            'registration_no' => 'nullable|string|max:50',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:M,F,O',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'course' => 'nullable|string|max:100',
            'batch' => 'nullable|integer|min:1900|max:2100',
            'cgpa' => 'nullable|numeric|min:0|max:10',
            'academic_details' => 'nullable|array',
            'training_status' => 'nullable|in:NOT_ASSIGNED,IN_TRAINING,COMPLETED',
        ];
    }
}
