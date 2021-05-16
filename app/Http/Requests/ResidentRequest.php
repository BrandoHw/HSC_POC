<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResidentRequest extends FormRequest
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
        $rules = [
            'resident_fName' => 'required',
            'resident_lName' => 'required',
            'resident_age' => 'required',
            'gender' => 'required',
        ];

        if($this->request->get('assign') == '1'){
            $rules['beacon_id'] = 'required';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        $custom_attributes = [
            'resident_fName' => 'first name',
            'resident_fName' => 'last name',
            'resident_age' => 'age',
        ];

        if($this->request->get('assign') == '1'){
            $custom_attributes['beacon_id'] = 'beacon';
        }

        return $custom_attributes;
    }    

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $custom_messages = [
            'gender.required' => 'Please select the :attribute.',
        ];

        if($this->request->get('assign') == '1'){
            $custom_messages['beacon_id.required'] = 'Please select the :attribute.';
        }

        return $custom_messages;
    }
}
