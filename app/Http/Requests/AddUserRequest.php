<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\IsUniqueTag;

class AddUserRequest extends FormRequest
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
            'fName' => 'required',
            'lName' => 'required',
            'username' => 'required|unique:users_table,username,NULL,user_id,deleted_at,NULL',
            'gender' => 'required',
            'email' => 'required|email|unique:users_table,email,NULL,user_id,deleted_at,NULL',
            'phone_number' => 'required|unique:users_table,phone_number,NULL,user_id,deleted_at,NULL',
            'role' => 'required',
            'type_id' => 'required'
        ];

        if($this->request->get('assign') == '1'){
            $rules['beacon_id'] = ['required', new IsUniqueTag(null)];
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
            'fName' => 'first name',
            'lName' => 'last name',
            'phone_number' => 'phone number',
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
            'role.required' => 'Please select the :attribute.',
            'type_id.required' => 'Please select the staff type',
        ];

        if($this->request->get('assign') == '1'){
            $custom_messages['beacon_id.required'] = 'Please select the :attribute.';
        }

        return $custom_messages;
    }
}
