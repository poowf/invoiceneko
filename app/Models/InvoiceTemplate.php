<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceTemplate extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'netdays',
        'notify',
        'client_id'
    ];

    public function getDateAttribute($value)
    {
        $date = $this->asDateTime($value);
        return $date->timezone($this->recurrence->company->timezone);
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function recurrence()
    {
        return $this->belongsTo('App\Models\InvoiceRecurrence', 'invoice_recurrence_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\InvoiceItemTemplate', 'invoice_template_id');
    }
}
