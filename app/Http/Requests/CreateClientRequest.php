<?php

namespace App\Http\Requests;

use App\Models\Client;
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
        return auth()->user()->can('create', Client::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'companyname' => 'required|string',
            'phone' => '',
            'block' => '',
            'street' => '',
            'unitnumber' => '',
            'postalcode' => '',
            'country_code' => '',
            'nickname' => '',
            'crn' => '',
            'website' => '',
            'contactsalutation' => 'required|in:mr,mrs,mdm,miss',
            'contactfirstname' => 'required|string',
            'contactlastname' => '',
            'contactgender' => '',
            'contactemail' => 'required|email',
            'contactphone' => '',
        ];
    }
}
