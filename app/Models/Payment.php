<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

use Carbon\Carbon;

class Payment extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

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
    public function getMoneyFormatAttribute()
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%!.2n', $this->amount);
    }

    public function getPercentageAttribute()
    {
        $invoiceTotal = $this->invoice->total;
        return money_format('%!.2n', ($this->amount/$invoiceTotal)*100);
    }

    public function getDateFormatAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->receiveddate)->format('j F, Y');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function scopeDuplicateCheck($query, $amount, $receiveddate, $invoiceid, $clientid, $companyid)
    {
        return $query
            ->where('amount', $amount)
            ->where('receiveddate', $receiveddate)
            ->where('invoice_id', $invoiceid)
            ->where('client_id', $clientid)
            ->where('company_id', $companyid);
    }
}
