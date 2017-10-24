<?php

namespace App\Http\Requests\Backend;

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
            'username' => 'required|min:4|unique:users,username,' . $this->route('user')->id,
            'email' => 'required|email|unique:users,email,' . $this->route('user')->id,
            'phone' => 'required|unique:users,phone,' . $this->route('user')->id,
            'full_name' => 'required',
            'gender' => 'required|in:male,female',
            'roles' => 'required',
            'status' => 'required',
        ];
    }
}
