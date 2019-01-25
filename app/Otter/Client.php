<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class Client extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Client';

    /**
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'companyname';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields()
    {
        return [
            'companyname'       => 'text',
            'phone'             => 'text',
            'crn'               => 'text',
            'contactsalutation' => 'text',
            'contactfirstname'  => 'text',
            'contactlastname'   => 'text',
            'contactemail'      => 'text',
            'contactphone'      => 'text',
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
}
