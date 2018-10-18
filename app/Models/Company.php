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
            $settings->quote_prefix = str_slug($company->name) . 'Q';
            $settings->invoice_conditions = "Terms & Conditions for your Invoice";
            $settings->quote_conditions = "Terms & Conditions for your Quote";
            $company->settings()->save($settings);
        });
    }

    public function quotes()
    {
        return $this->hasMany('App\Models\Quote', 'company_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'company_id');
    }

    public function lastinvoice()
    {
        return $this->hasOne('App\Models\Invoice')->latest()->limit(1)->first();
    }

    public function lastquote()
    {
        return $this->hasOne('App\Models\Quote')->latest()->limit(1)->first();
    }


    public function niceInvoiceID()
    {
        $companysettings = $this->settings;
        if($companysettings->invoice_prefix)
        {
            $prefix = $companysettings->invoice_prefix . '-';
        }
        else
        {
            $prefix = $this->slug . '-';
        }
        return $prefix . sprintf('%06d', $this->invoice_index);
    }

    public function niceQuoteID()
    {
        $companysettings = $this->settings;
        if($companysettings->quote_prefix)
        {
            $prefix = $companysettings->quote_prefix . '-';
        }
        else
        {
            $prefix = $this->slug . 'Q-';
        }
        return $prefix . sprintf('%06d', $this->quote_index);
    }

    public function isOwner(User $user)
    {
        return $this->user_id == $user->id;
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
