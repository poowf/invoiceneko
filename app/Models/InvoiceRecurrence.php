<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class InvoiceRecurrence extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_recurrences';

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'invoice_recurrence_id');
    }

    public function template()
    {
        return $this->hasOne('App\Models\InvoiceTemplate', 'invoice_recurrence_id');
    }
}
