<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class Quote extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Quote';

    /**
     * Get the fields and types used by the resource
     *
     * @return array
     */
    public function fields()
    {
        return [
            'name' => 'text',
            'password' => 'password',
            'email' => 'email',
            'first_name' => 'text',
            'example-field' => 'type',
        ];
    }

    /**
     * Fields to be hidden in the resource collection
     *
     * @return array
     */
    public function hidden()
    {
        return [
            'password'
        ];
    }
}