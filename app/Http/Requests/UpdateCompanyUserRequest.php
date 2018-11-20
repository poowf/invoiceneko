<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyUserRequest extends FormRequest
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
        //this->user works because the user model is in the path
        return [
            'username' => 'required|string|min:4|unique:users,username,' . $this->user->id,
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'phone' => 'required|unique:users,phone,' . $this->user->id,
            'gender' => 'required|in:male,female',
            'full_name' => 'required|string|min:4',
            'newpassword' => 'confirmed',
        ];
    }
}
