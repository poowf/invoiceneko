<?php

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class ItemTemplate extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'item_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'quantity',
        'price',
        'description',
    ];


    public function duplicate()
    {
        $cloned = $this->replicate();
        $cloned->save();

        return $cloned;
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
