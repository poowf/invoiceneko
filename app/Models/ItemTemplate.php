<?php

namespace App\Models;

use App\Library\Poowf\Unicorn;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class ItemTemplate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasFactory, CascadeSoftDeletes;

    protected $table = 'item_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'quantity', 'price', 'description'];

    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = Unicorn::stripUnwantedTagsAndAttrs($description, ENT_COMPAT, 'UTF-8');
    }

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
