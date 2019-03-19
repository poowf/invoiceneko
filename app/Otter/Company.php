<?php

namespace App\Otter;

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
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields()
    {
        return [
            'name'         => 'text',
            'crn'          => 'text',
            'domain_name'  => 'text',
            'phone'        => 'text',
            'email'        => 'email',
            'country_code' => 'text',
            'timezone'     => 'text',
        ];
    }

    public static function relations()
    {
        return [
            'settings' => 'CompanySetting',
            'users'    => 'User',
            'invoices' => 'Invoice',
            'receipts' => 'Receipt',
            'owner'    => 'User',
        ];
    }
}
