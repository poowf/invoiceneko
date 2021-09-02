<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Payment extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'receiveddate',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the price.
     *
     * @param string $value
     *
     * @return string
     */
    public function getMoneyFormatAttribute()
    {
        $amount = new \NumberFormatter( 'en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, "* #####.00 ;(* #####.00)");

        return $amount->format($this->amount);
    }

    public function getPercentageAttribute()
    {
        $invoiceTotal = $this->invoice->total;
        if ($invoiceTotal == 0 && $this->amount == 0) {
            $percentage = 100;
        } else {
            $percentage = ($invoiceTotal != 0) ? ($this->amount / $invoiceTotal) * 100 : 0;
        }

        return $amount->format($percentage);
    }

    public function getCreatedAtAttribute($value)
    {
        $date = $this->asDateTime($value);

        return (auth()->user()) ? $date->timezone(auth()->user()->timezone) : $date->timezone(config('app.timezone'));
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = $this->asDateTime($value);

        return (auth()->user()) ? $date->timezone(auth()->user()->timezone) : $date->timezone(config('app.timezone'));
    }

    public function getReceiveddateAttribute($value)
    {
        $date = $this->asDateTime($value);

        return $date->timezone($this->company->timezone);
    }

    public function getClient()
    {
        return ($this->client) ? $this->client : (object) json_decode($this->invoice->client_data);
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
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
