<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UpdateRoleRequest extends FormRequest
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
            'title' => [
                'required',
                Rule::unique('roles')->where('scope', auth()->user()->company_id)->ignore(Bouncer::role()->where('name', $this->role)->first()->id),
                Rule::unique('roles', 'name')->where('scope', auth()->user()->company_id)->ignore(Bouncer::role()->where('name', $this->role)->first()->id)
            ],
            'permissions' => 'required|array'
        ];
    }
}
