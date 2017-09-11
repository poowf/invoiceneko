<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

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
        'address',
        'nickname',
        'crn',
        'contactname',
        'contactgender',
        'contactemail',
        'contactphone',
    ];

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'client_id');
    }
}
