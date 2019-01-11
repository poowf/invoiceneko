<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class CompanySetting extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\CompanySetting';

    /**
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'invoice_prefix';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields ()
    {
        return [
            'invoice_prefix'     => 'text',
            'quote_prefix'       => 'text',
            'receipt_prefix'     => 'text',
            'invoice_conditions' => 'text',
            'quote_conditions'   => 'text',
            'tax'                => 'text',
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
}
