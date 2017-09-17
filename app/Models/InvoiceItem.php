<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class InvoiceItem extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_items';

    /**
     * Get the price.
     *
     * @param  string  $value
     * @return string
     */
    public function getPriceAttribute($value)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%!.2n', $value);
    }


    public function scopeDuplicateCheck($query, $name, $description, $price, $quantity, $invoiceid)
    {
        return $query
            ->where('name', $name)
            ->where('description', $description)
            ->where('price', $price)
            ->where('quantity', $quantity)
            ->where('invoice_id', $invoiceid);
    }
}
