<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySettings extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_settings';

    public $timestamps = true;

    protected $fillable = [
        'invoice_prefix',
        'quote_prefix',
        'invoice_conditions',
        'quote_conditions',
        'tax'
    ];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
