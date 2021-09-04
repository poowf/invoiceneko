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
     * The column of the model to display in select options.
     *
     * @var string
     */
    public static $title = 'nice_invoice_id';

    /**
     * Get the fields and types used by the resource.
     *
     * @return array
     */
    public static function fields()
    {
        return [
            'nice_invoice_id' => 'text',
            'date' => 'text',
            'netdays' => 'text',
            'total' => 'text',
            'status' => 'text',
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
            'client' => ['Client', 'client_id'],
            'company' => 'Company',
            'items' => 'InvoiceItem',
            'receipt' => 'Receipt',
        ];
    }

    public function toArray($request)
    {
        $transformed = parent::toArray($request);
        $transformed['date'] = $this->date ? $this->date->format('Y-m-d H:i:s') : null;

        return $transformed;
    }
}
