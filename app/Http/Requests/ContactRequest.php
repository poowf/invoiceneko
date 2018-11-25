<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;

class ContactRequest extends FormRequest {

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
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'g-recaptcha-response.required' => 'Captcha verification is required',
            'g-recaptcha-response.captcha'  => 'Captcha verification has failed'
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => [
                'required',
                new CaptchaRule
            ]
        ];
    }

}