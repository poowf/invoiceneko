<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class Receipt extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Receipt';

    /**
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'nice_receipt_id';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields()
    {
        return [
            'nice_receipt_id' => 'text',
        ];
    }

    /**
     * Fields to be hidden in the resource collection.
     *
     * @return array
     */
    public static function hidden()
    {
        return [
        ];
    }

    /**
     * Get the validation rules used by the resource.
     *
     * @return array
     */
    public static function validations()
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

    /**
     * Get the relations used by the resource.
     *
     * @return array
     */
    public static function relations()
    {
        return [
            'invoice' => 'Invoice',
            'company' => 'Company',
        ];
    }
}
