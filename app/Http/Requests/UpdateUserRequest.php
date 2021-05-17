<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
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
        $user = User::find($this->request->get('user_id'));

        $rules = [
            'fName' => 'required',
            'lName' => 'required',
            'username' => 'required|unique:users_table,username,'.$user->user_id.',user_id',
            'gender' => 'required',
            'email' => 'required|email|unique:users_table,email,'.$user->user_id.',user_id',
            'phone_number' => 'required|unique:users_table,phone_number,'.$user->user_id.',user_id',
        ];

        $auth_user = Auth::user();
        $permissions = $auth_user->getPermissionsViaRoles()->pluck('name')->all();

        if(in_array('user-edit', $permissions) || in_array('beacon-edit', $permissions)){
            $rules['role'] = 'required';

            if($this->request->get('assign') == '1'){
                $rules['beacon_id'] = 'required';
            }
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

        $auth_user = Auth::user();
        $permissions = $auth_user->getPermissionsViaRoles()->pluck('name')->all();

        if(in_array('user-edit', $permissions) || in_array('beacon-edit', $permissions)){
            if($this->request->get('assign') == '1'){
                $custom_attributes['beacon_id'] = 'beacon';
            }
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
        ];

        $auth_user = Auth::user();
        $permissions = $auth_user->getPermissionsViaRoles()->pluck('name')->all();

        if(in_array('user-edit', $permissions) || in_array('beacon-edit', $permissions)){
            $custom_messages['role.required'] = 'Please select the :attribute.';

            if($this->request->get('assign') == '1'){
                $custom_messages['beacon_id.required'] = 'Please select the :attribute.';
            }
        }

        return $custom_messages;
    }
}
