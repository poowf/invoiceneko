<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class CompanyAddress extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasFactory, CascadeSoftDeletes;

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
