<?php

namespace App\Http\Requests;
use App\Rules\IsUniqueTag;

use Illuminate\Foundation\Http\FormRequest;

class UpdateResidentRequest extends FormRequest
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
            'resident_dob' => 'required|date',
            'resident_gender' => 'required',
            'location_room_id' => 'required',
            'contact_name' => 'required',
            'contact_phone_num_1' => 'required',
            'contact_address' => 'required',
            'contact_relationship' => 'required',
        ];

        if($this->request->get('assign') == '1'){
            $rules['beacon_id'] = ['required', new IsUniqueTag($resident->tag ?? null)];
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
            'resident_lName' => 'last name',
            'resident_dob' => 'date of birth',
            'resident_gender' => 'gender',
            'location_room_id' => 'room',
            'contact_name' => 'name',
            'contact_phone_num_1' => 'phone number',
            'contact_address' => 'address',
            'contact_relationship' => 'relationship',
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
            'resident_dob.required' => 'Please select the :attribute.',
            'resident_gender.required' => 'Please select the :attribute.',
            'location_room_id.required' => 'Please select the :attribute.',
            'contact_relationship.required' => 'Please select the :attribute.',
        ];

        if($this->request->get('assign') == '1'){
            $custom_messages['beacon_id.required'] = 'Please select the :attribute.';
        }

        return $custom_messages;
    }
}
