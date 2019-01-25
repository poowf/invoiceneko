<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'username'  => 'required|min:4|unique:users',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|confirmed',
            'full_name' => 'required|min:4',
            'phone'     => 'required|unique:users',
            'gender'    => 'required|in:male,female',
        ];
    }
}
