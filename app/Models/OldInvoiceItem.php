<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OldInvoiceItem extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'old_invoice_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
    ];

    public function invoice()
    {
        return $this->belongsTo('App\Models\OldInvoice', 'old_invoice_id');
    }

    public function moneyFormatPrice()
    {
        $amount = new \NumberFormatter( 'en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, "* #####.00 ;(* #####.00)");

        return $amount->format($this->price);
    }
}
