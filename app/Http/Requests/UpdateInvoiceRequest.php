<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('update', $this->route('invoice'));
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $messages = [];

        foreach ($this->request->get('item_name') as $key => $value) {
            $messages['item_name.'.$key.'.required'] = 'Item Name #'.$key.'  is required';
        }

        foreach ($this->request->get('item_quantity') as $key => $value) {
            $messages['item_quantity.'.$key.'.required'] = 'Item Quantity #'.$key.'  is required';
        }

        foreach ($this->request->get('item_price') as $key => $value) {
            $messages['item_price.'.$key.'.required'] = 'Item Price #'.$key.'  is required';
        }

        return $messages;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date'                             => 'required|date_format:"j F, Y"',
            'netdays'                          => 'required|integer|min:0',
            'item_name'                        => 'required|array',
            'item_name.*'                      => 'required|string',
            'item_quantity'                    => 'required|array',
            'item_quantity.*'                  => 'required|integer|min:1',
            'item_price'                       => 'required|array',
            'item_price.*'                     => 'required|numeric',
            'item_description'                 => 'nullable|array',
            'item_description.*'               => 'nullable|string',
            'recurring-details'                => 'required|in:none,standalone,future',
            'recurring-time-interval'          => 'required_if:recurring-invoice-check,on|integer',
            'recurring-time-period'            => 'required_if:recurring-invoice-check,on|in:day,week,month,year',
            'recurring-until'                  => 'required_if:recurring-invoice-check,on|in:never,occurence,date',
            'recurring-until-occurence-number' => 'required_if:recurring-invoice-check,on|integer',
            'recurring-until-date-value'       => 'required_if:recurring-invoice-check,on|date_format:"j F, Y"',
        ];
    }
}
