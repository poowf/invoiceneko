<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class Payment extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Payment';

    /**
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields ()
    {
        return [
            'amount' => 'text',
            'mode'   => 'text',
            'notes'  => 'text',
        ];
    }

    /**
     * Fields to be hidden in the resource collection.
     *
     * @return array
     */
    public static function hidden ()
    {
        return [
        ];
    }

    /**
     * Get the relations used by the resource.
     *
     * @return array
     */
    public static function relations ()
    {
        return [
            'company' => 'Company',
            'invoice' => 'Invoice',
        ];
    }

    /**
     * Get the validation rules used by the resource.
     *
     * @return array
     */
    public static function validations ()
    {
        return [
        'client' => [
            'create' => [
                /*
                * Client side create resource validation
                */
            ],
            'update' => [
                /*
                * Client side update resource validation
                */
            ],
        ],
        'server' => [
            'create' => [
                /*
                * Server side create resource validation
                */
            ],
            'update' => [
                /*
                * Server side update resource validation
                */
            ],
        ],
    ];
    }
}
