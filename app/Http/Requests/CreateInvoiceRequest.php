<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->can('create', Invoice::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|date_format:"j F, Y"',
            'netdays' => 'required|integer|min:0',
            'client_id' => 'required',
            'item_name.*' => 'required|string',
            'item_quantity.*' => 'required|integer|min:1',
            'item_price.*' => 'required|numeric',
            'item_description.*' => 'string',
            'recurring-time-interval' => 'required_if:recurring-invoice-check,on|integer|min:1',
            'recurring-time-period' => 'required_if:recurring-invoice-check,on|in:day,week,month,year',
            'recurring-until' => 'required_if:recurring-invoice-check,on|in:never,occurence,date',
            'recurring-until-occurence-number' => 'required_if:recurring-invoice-check,on|integer|min:1',
            'recurring-until-date-value' => 'required_if:recurring-invoice-check,on|date_format:"j F, Y"',
        ];
    }
}
