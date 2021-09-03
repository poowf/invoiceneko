<?php

namespace App\Models;

use App\Library\Poowf\Unicorn;
use App\Traits\UniqueSlug;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class Company extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasRolesAndAbilities, HasFactory, Notifiable, SoftDeletes, CascadeSoftDeletes, UniqueSlug;

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
        'domain_name',
        'phone',
        'email',
        'country_code',
        'timezone',
    ];

    protected $attributes = [
        'invoice_index' => 1,
        'quote_index'   => 1,
        'receipt_index' => 1,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($company) {
            $company->slug = Str::slug($company->name);
            static::generateSlug($company);
        });

        //Auto Creation of Settings per Company;
        static::created(function ($company) {
            $settings = new CompanySetting();
            $company->settings()->save($settings);
            Unicorn::createRoleAndPermissions($company->id);
            Bouncer::assign('global-administrator')->to($company->owner);
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'domain_name';
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function niceInvoiceID()
    {
        return $this->generateNiceID('invoice', '');
    }

    public function niceQuoteID()
    {
        return $this->generateNiceID('quote', 'Q');
    }

    public function niceReceiptID()
    {
        return $this->generateNiceID('receipt', 'R');
    }

    public function generateNiceID($model, $letter)
    {
        $companySetting = $this->settings;
        if ($companySetting->{$model . '_prefix'}) {
            $generatedPrefix = $companySetting->{$model . '_prefix'} . '-';
        } else {
            $generatedPrefix = $this->slug . $letter . '-';
        }

        //Retrieve latest version of the company model otherwise it will use the old index value
        $this->refresh();

        return $generatedPrefix . sprintf('%06d', $this->{$model . '_index'});
    }

    public function isOwner($user)
    {
        return $this->user_id == $user->id;
    }

    public function hasUser($user)
    {
        return $this->users->contains($user);
    }

    public function lastinvoice()
    {
        return $this->hasOne('App\Models\Invoice')->latest()->limit(1)->first();
    }

    public function lastquote()
    {
        return $this->hasOne('App\Models\Quote')->latest()->limit(1)->first();
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function requests()
    {
        return $this->hasMany('App\Models\CompanyUserRequest', 'company_id');
    }

    public function invites()
    {
        return $this->hasMany('App\Models\CompanyInvite', 'company_id');
    }

    public function quotes()
    {
        return $this->hasMany('App\Models\Quote', 'company_id');
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'company_id');
    }

    public function receipts()
    {
        return $this->hasMany('App\Models\Receipt', 'company_id');
    }

    public function events()
    {
        return $this->hasMany('App\Models\InvoiceRecurrence', 'company_id');
    }

    public function itemtemplates()
    {
        return $this->hasMany('App\Models\ItemTemplate', 'company_id');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client', 'company_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'company_id');
    }

    /**
     * Get all of the company's recipients.
     */
    public function recipients()
    {
        return $this->morphMany('App\Models\Recipient', 'recipientable');
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
        return $this->hasOne('App\Models\CompanySetting', 'company_id');
    }
}
