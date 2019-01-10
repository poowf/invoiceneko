<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update', $this->route('quote'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'               => 'required|date_format:"j F, Y"',
            'netdays'            => 'required|integer|min:0',
            'item_name.*'        => 'required|string',
            'item_quantity.*'    => 'required|integer|min:1',
            'item_price.*'       => 'required|numeric',
            'item_description.*' => 'string',
        ];
    }
}
