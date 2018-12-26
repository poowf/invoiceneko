<?php

namespace App\Otter;

use Illuminate\Http\Request;
use Poowf\Otter\Http\Resources\OtterResource;

class Company extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Company';

    /**
     * Get the fields and types used by the resource
     *
     * @return array
     */
    public function fields()
    {
        return [
            'name' => 'text',
            'crn' => 'text',
            'domain_name' => 'text',
            'phone' => 'text',
            'email' => 'email',
            'country_code' => 'text',
            'timezone' => 'text',
        ];
    }
}