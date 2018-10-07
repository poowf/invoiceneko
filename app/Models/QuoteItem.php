<?php

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class QuoteItem extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quote_items';

    public function quote()
    {
        return $this->belongsTo('App\Models\Quote', 'quote_id');
    }

    public function moneyFormatPrice()
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%!.2n', $this->price);
    }

    public function scopeDuplicateCheck($query, $price, $quantity, $invoiceid)
    {
        return $query
            ->where('price', $price)
            ->where('quantity', $quantity)
            ->where('invoice_id', $invoiceid);
    }
}
