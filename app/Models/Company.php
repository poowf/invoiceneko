<?php

namespace App\Models;

use Log;
use App\Traits\UniqueSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Company extends Model
{
    use SoftDeletes, CascadeSoftDeletes, UniqueSlug;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'crn',
        'phone',
        'email',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($company) {
            $company->slug = str_slug($company->name);
            static::generateSlug($company);
        });

        //Auto Creation of Settings per Company;
        static::created(function ($company) {
            $settings = new CompanySettings;
            $settings->invoice_prefix = str_slug($company->name);
            $company->settings()->save($settings);
        });
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'company_id');
    }

    public function lastinvoice()
    {
        return $this->hasOne('App\Models\Invoice')->latest()->limit(1)->first();
    }

    public function niceInvoiceID()
    {
        $invoice = $this->lastinvoice();

        $nice_invoice_id = $invoice->nice_invoice_id;

        $currentindex = preg_split('#^.*-#s', $nice_invoice_id);

        $currentindex[1] += 1;

        return sprintf('%06d', $currentindex[1]);
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client', 'company_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'company_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function address()
    {
        return $this->hasOne('App\Models\CompanyAddress', 'company_id');
    }

    public function settings()
    {
        return $this->hasOne('App\Models\CompanySettings', 'company_id');
    }
}
