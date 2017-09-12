<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class Company extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

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
        'slug',
        'crn',
    ];

    public function invoices()
    {
        return $this->hasMany('App\Models\Invoice', 'company_id');
    }

    public function clients()
    {
        return $this->hasMany('App\Models\Client', 'company_id');
    }
}
