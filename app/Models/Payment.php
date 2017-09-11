<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount',
        'mode',
        'notes',
    ];

    /**
     * Get the price.
     *
     * @param  string  $value
     * @return string
     */
    public function getAmountAttribute($value)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%!.2n', $value);
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }
}
