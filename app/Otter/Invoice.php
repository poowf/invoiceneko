<?php

namespace App\Otter;

use Illuminate\Http\Request;
use Poowf\Otter\Http\Resources\OtterResource;

class Invoice extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Invoice';

    /**
     * Get the fields and types used by the resource
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            'name' => 'text',
            'password' => 'password',
            'email' => 'email',
            'first_name' => 'text',
            'example-field' => 'type',
        ];
    }
}