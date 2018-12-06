<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
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
     * @param $model
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function owner($model)
    {
        return $this->morphedByMany($model, 'recipientable');
    }

    /**
     * Get all of the recipients that are associated with a client
     */
    public function clients()
    {
        return $this->morphedByMany('App\Models\Client', 'recipientable');
    }

    /**
     * Get all of the recipients that are associated with a company
     */
    public function companies()
    {
        return $this->morphedByMany('App\Models\Company', 'recipientable');
    }

    /**
     * Get the company that owns the recipient.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
}
