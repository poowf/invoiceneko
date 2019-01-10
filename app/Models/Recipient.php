<?php

namespace App\Models;

use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class Recipient extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'recipients';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'salutation',
        'first_name',
        'last_name',
        'email',
        'phone',
    ];

    public function getNameAttribute()
    {
        return ucwords("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get all of the owning recipientable models.
     */
    public function recipientable()
    {
        return $this->morphTo();
    }

    /**
     * Get the company that owns the recipient.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}
