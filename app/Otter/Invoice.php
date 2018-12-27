<?php

namespace App\Otter;

use Poowf\Otter\Http\Resources\OtterResource;

class Invoice extends OtterResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Models\Invoice';

    /**
     * Get the fields and types used by the resource
     *
     * @return array
     */
    public function fields()
    {
        return [
            'nice_invoice_id' => 'text',
            'date' => 'text',
            'netdays' => 'text',
            'total' => 'text',
            'status' => 'text',
        ];
    }

    public function toArray($request)
    {
        $transformed = parent::toArray($request);
        $transformed['date'] = $this->date ? $this->date->format('Y-m-d H:i:s') : null;

        return $transformed;
    }
}