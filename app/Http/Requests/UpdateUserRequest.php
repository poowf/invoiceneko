<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'username'    => 'required|string|min:4|unique:users,username,' . auth()->user()->id,
            'email'       => 'required|email|unique:users,email,' . auth()->user()->id,
            'password'    => 'required',
            'phone'       => 'required|unique:users,phone,' . auth()->user()->id,
            'gender'      => 'required|in:male,female',
            'full_name'   => 'required|string|min:4',
            'newpassword' => 'confirmed',
        ];
    }
}
