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
    public static $model = 'App\Models\Quote';

    /**
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'nice_quote_id';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields()
    {
        return [
            'nice_quote_id' => 'text',
            'date' => 'text',
            'netdays' => 'text',
            'total' => 'text',
            'status' => 'text',
        ];
    }

    /**
     * Fields to be hidden in the resource collection.
     *
     * @return array
     */
    public static function hidden()
    {
        return [];
    }

    public function toArray($request)
    {
        $transformed = parent::toArray($request);
        $transformed['date'] = $this->date ? $this->date->format('Y-m-d H:i:s') : null;

        return $transformed;
    }
}
