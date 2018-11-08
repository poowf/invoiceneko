<?php

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class InvoiceEvent extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_events';

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'invoice_event_id');
    }

    public function template()
    {
        return $this->hasOne('App\Models\InvoiceTemplate', 'invoice_event_id');
    }
}
