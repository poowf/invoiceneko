<?php

namespace App\Http\Requests;

use App\Models\ItemTemplate;
use Illuminate\Foundation\Http\FormRequest;

class CreateItemTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize ()
    {
        return auth()->user()->can('create', ItemTemplate::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules ()
    {
        return [
            'name'        => 'required|string',
            'quantity'    => 'required|integer|min:1',
            'price'       => 'required|numeric',
            'description' => 'string',
        ];
    }
}
