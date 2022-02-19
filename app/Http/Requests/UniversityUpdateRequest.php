<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UniversityUpdateRequest extends FormRequest
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
            'name'=> 'string|max:255',
            'address'=> 'string|max:255',
            'link'=> 'string|max:255',
            'tuitionfees'=> 'string|max:255',
            'intake'=> 'string|max:255'
        ];
    }
}
