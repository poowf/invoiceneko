<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class CreateRecipientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return auth()->user()->can('create', Client::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'salutation' => 'required|in:mr,mrs,mdm,miss,dr,prof,mx',
            'first_name' => 'required|string',
            'last_name'  => '',
            'email'      => 'required|email|unique:recipients',
            'phone'      => '',
        ];
    }
}
