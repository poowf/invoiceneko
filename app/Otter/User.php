<?php

namespace App\Otter;

use Illuminate\Http\Request;
use Poowf\Otter\Http\Resources\OtterResource;

class User extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\User';

    /**
     * Get the fields and types used by the resource
     *
     * @return array
     */
    public function fields()
    {
        return [
            'full_name' => 'text',
            'username' => 'text',
            'password' => 'password',
            'email' => 'email',
            'phone' => 'text',
            'gender' => 'text',
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