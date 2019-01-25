<?php

namespace App\Http\Requests;

use App\Rules\Hostname;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('owner', $this->route('company'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|string',
            'crn'         => 'required|string',
            'phone'       => 'required|string',
            'email'       => 'required|email',
            'domain_name' => ['required', 'unique:companies,domain_name,' . $this->route('company')->id, new Hostname()],
        ];
    }
}
