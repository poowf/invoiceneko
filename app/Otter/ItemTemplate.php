<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class ItemTemplate extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\ItemTemplate';

    /**
     * The column of the model to display in select options
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * Get the fields and types used by the resource
     *
     * @return array
     */
    public static function fields()
    {
        return [
            'name' => 'text',
            'quantity' => 'text',
            'price' => 'text',
        ];
    }

    /**
     * Fields to be hidden in the resource collection
     *
     * @return array
     */
    public static function hidden()
    {
        return [
        ];
    }

    /**
    * Get the validation rules used by the resource
    *
    * @return array
    */
    public static function validations()
    {
        return [
        'client' => [
            'create' => [
                /**
                * Client side create resource validation
                */
            ],
            'update' => [
                /**
                * Client side update resource validation
                */
            ]
        ],
        'server' => [
            'create' => [
                /**
                * Server side create resource validation
                */
            ],
            'update' => [
                /**
                * Server side update resource validation
                */
            ]
        ],
    ];
    }
}