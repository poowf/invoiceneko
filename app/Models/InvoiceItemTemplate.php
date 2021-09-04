<?php

namespace App\Models;

use App\Library\Poowf\Unicorn;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class InvoiceItemTemplate extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;
    use HasFactory;
    use CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoice_item_templates';

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

    public function template()
    {
        return $this->belongsTo('App\Models\InvoiceTemplate', 'invoice_template_id');
    }
}
