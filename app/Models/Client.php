<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Client extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasFactory, CascadeSoftDeletes;

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

    protected $cascadeDeletes = ['invoices'];

    public function getContactNameAttribute()
    {
        return "{$this->contactfirstname} {$this->contactlastname}";
    }

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'client_id');
    }

    /**
     * Get all of the client's recipients.
     */
    public function recipients()
    {
        return $this->morphMany('App\Models\Recipient', 'recipientable');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function scopeDuplicateCheck($query, $companyname)
    {
        return $query->where('companyname', $companyname);
    }
}
