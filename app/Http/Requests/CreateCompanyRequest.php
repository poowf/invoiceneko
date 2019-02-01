<?php

namespace App\Http\Requests;

use App\Rules\Hostname;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
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
            'name'        => 'required|string',
            'crn'         => 'required|string',
            'phone'       => 'required',
            'email'       => 'required|email',
            'domain_name' => ['required', 'unique:companies', new Hostname()],
            'logo'        => 'mimes:jpeg,bmp,png',
            'smlogo'      => 'mimes:jpeg,bmp,png',
        ];
    }
}
