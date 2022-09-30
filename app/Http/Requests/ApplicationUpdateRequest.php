<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'university_name' => 'string|max:255',
            'course_level' => 'string|max:255',
            'course_name' => 'string|max:255',
            'student_name' => 'string|max:255',
            'student_email' => 'email|string||max:255',
            'student_number' => 'string|max:255',
            'student_dob' => 'date',
            'visa_refusal' => 'boolean',
            'nationality' => 'string|max:255',
        ];
    }
}
