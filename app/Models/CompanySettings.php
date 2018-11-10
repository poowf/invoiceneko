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

    protected $fillable = [
        'invoice_prefix',
        'quote_prefix',
        'invoice_conditions',
        'quote_conditions',
        'tax'
    ];

    protected $attributes = [
        'invoice_conditions' => 'Terms & Conditions for your Invoice',
        'quote_conditions' => 'Terms & Conditions for your Quote',
        'tax' => 0,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($companySettings) {
            $companySettings->invoice_prefix = str_slug($companySettings->company->name);
            $companySettings->quote_prefix = str_slug($companySettings->company->name) . 'Q';
        });
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
