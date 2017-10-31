<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyAddress extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_addresses';

    public $timestamps = true;

    protected $fillable = [
        'block',
        'street',
        'unitnumber',
        'postalcode',
        'buildingtype',
    ];

    const BUILDINGTYPE_RESIDENTIAL = 1;
    const BUILDINGTYPE_BUSINESS = 2;

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
