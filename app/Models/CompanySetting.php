<?php

namespace App\Models;

use App\Library\Poowf\Unicorn;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class CompanySetting extends Model implements Auditable
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
    protected $table = 'company_settings';

    protected $fillable = ['invoice_prefix', 'quote_prefix', 'receipt_prefix', 'invoice_conditions', 'quote_conditions', 'tax'];

    protected $attributes = [
        'invoice_conditions' => 'Terms & Conditions for your Invoice',
        'quote_conditions' => 'Terms & Conditions for your Quote',
        'tax' => 0,
    ];

    public function setInvoiceConditionsAttribute($description)
    {
        $this->attributes['invoice_conditions'] = Unicorn::stripUnwantedTagsAndAttrs($description, ENT_COMPAT, 'UTF-8');
    }

    public function setQuoteConditionsAttribute($description)
    {
        $this->attributes['quote_conditions'] = Unicorn::stripUnwantedTagsAndAttrs($description, ENT_COMPAT, 'UTF-8');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($companySettings) {
            $companySettings->invoice_prefix = Str::slug($companySettings->company->name);
            $companySettings->quote_prefix = Str::slug($companySettings->company->name) . 'Q';
            $companySettings->receipt_prefix = Str::slug($companySettings->company->name) . 'R';
        });
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }
}
