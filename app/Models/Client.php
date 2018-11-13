<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Client extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'companyname',
        'phone',
        'block',
        'street',
        'unitnumber',
        'postalcode',
        'country_code',
        'nickname',
        'crn',
        'website',
        'contactsalutation',
        'contactfirstname',
        'contactlastname',
        'contactgender',
        'contactemail',
        'contactphone',
    ];

    protected $cascadeDeletes = [
        'invoices',
    ];

    public function getContactNameAttribute()
    {
        return "{$this->contactfirstname} {$this->contactlastname}";
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'client_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function scopeDuplicateCheck($query, $companyname)
    {
        return $query
            ->where('companyname', $companyname);
    }
}
