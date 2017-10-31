<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClientRequest extends FormRequest
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
            'companyname' => 'required',
            'phone' => '',
            'block' => '',
            'street' => '',
            'unitnumber' => '',
            'postalcode' => '',
            'country' => '',
            'nickname' => '',
            'crn' => '',
            'website' => '',
            'contactsalutation' => 'required',
            'contactfirstname' => 'required',
            'contactlastname' => '',
            'contactgender' => '',
            'contactemail' => 'required',
            'contactphone' => '',
        ];
    }
}
