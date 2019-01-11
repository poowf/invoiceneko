<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return auth()->user()->can('owner', $this->route('company'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'title' => [
                'required',
                Rule::unique('roles')->where('scope', $this->route('company')->id)->ignore($this->role->id),
                Rule::unique('roles', 'name')->where('scope', $this->route('company')->id)->ignore($this->role->id),
            ],
            'permissions' => 'required|array',
        ];
    }
}
