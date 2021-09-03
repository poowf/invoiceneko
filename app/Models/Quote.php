<?php

namespace App\Models;

use Carbon\Carbon;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use PDF;

class Quote extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, HasFactory, CascadeSoftDeletes;

    const STATUS_DRAFT = 1;
    const STATUS_OPEN = 2;
    const STATUS_EXPIRED = 3;
    const STATUS_COMPLETED = 4;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quotes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date',
        'netdays',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
        'duedate',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $attributes = [
        'status' => self::STATUS_OPEN,
    ];

    protected $cascadeDeletes = [
        'items',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($quote) {
            if ($quote->status == self::STATUS_DRAFT) {
                $quote->status = self::STATUS_OPEN;
            }
            $date = clone $quote->date;
            $quote->duedate = $date->timezone(config('app.timezone'))->startOfDay()->addDays($quote->netdays);
        });

        //Auto Increment of quote_index per Company;
        static::created(function ($quote) {
            $company = $quote->company;
            $company->quote_index = $company->quote_index + 1;
            $company->save();
        });
    }

    public function getTotalMoneyFormatAttribute()
    {
        $amount = new \NumberFormatter('en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, '* #####.00 ;(* #####.00)');

        return $amount->format($this->total);
    }

    public function getCreatedAtAttribute($value)
    {
        $date = $this->asDateTime($value);

        return (auth()->user()) ? $date->timezone(auth()->user()->timezone) : $date->timezone(config('app.timezone'));
    }

    public function getUpdatedAtAttribute($value)
    {
        $date = $this->asDateTime($value);

        return (auth()->user()) ? $date->timezone(auth()->user()->timezone) : $date->timezone(config('app.timezone'));
    }

    public function getDateAttribute($value)
    {
        $date = $this->asDateTime($value);

        return $date->timezone($this->company->timezone);
    }

    public function getDuedateAttribute($value)
    {
        $date = $this->asDateTime($value);

        return $date->timezone($this->company->timezone);
    }

    public function setDateAttribute($value)
    {
        if ($value instanceof \DateTime) {
            $this->attributes['date'] = $value;
        } else {
            $this->attributes['date'] = $value = Carbon::createFromFormat('j F, Y', $value)->startOfDay();
        }
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\QuoteItem', 'quote_id');
    }

    public function getClient()
    {
        return ($this->client) ? $this->client : (object) json_decode($this->client_data);
    }

    public function owns($model)
    {
        return $this->id == $model->invoice_id;
    }

    public function calculatesubtotal($moneyformat = true)
    {
        $items = $this->items;
        $total = 0;

        foreach ($items as $item) {
            $itemtotal = $item->quantity * $item->price;

            $total += $itemtotal;
        }

        if ($moneyformat) {
            $amount = new \NumberFormatter('en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, '* #####.00 ;(* #####.00)');

            return $amount->format($total);
        } else {
            return $total;
        }
    }

    public function calculatetax($moneyformat = true)
    {
        $companySetting = $this->company->settings;
        $tax = 0;

        if ($companySetting->tax && $companySetting->tax != 0) {
            $tax = $companySetting->tax;
        }

        $subtotal = $this->calculatesubtotal(false);
        $subtotalWithTax = $subtotal * $tax;

        $tax = ($subtotalWithTax != 0) ? $subtotalWithTax / 100 : 0;

        if ($moneyformat) {
            $amount = new \NumberFormatter('en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, '* #####.00 ;(* #####.00)');

            return $amount->format($tax);
        } else {
            return $tax;
        }
    }

    public function calculatetotal($moneyformat = true)
    {
        $companySetting = $this->company->settings;
        $tax = 0;

        if ($companySetting->tax && $companySetting->tax != 0) {
            $tax = $companySetting->tax;
        }

        $subtotal = $this->calculatesubtotal(false);
        $totalWithTax = $subtotal * (100 + $tax);

        $total = ($totalWithTax != 0) ? $totalWithTax / 100 : 0;

        if ($moneyformat) {
            $amount = new \NumberFormatter('en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, '* #####.00 ;(* #####.00)');

            return $amount->format($total);
        } else {
            return $total;
        }
    }

    public function setQuoteTotal()
    {
        $this->total = self::calculatetotal(false);
        $this->save();
    }

    public function statusText()
    {
        $status = $this->status;

        switch ($status) {
            default:
                $textstatus = 'Open';
                break;
            case self::STATUS_DRAFT:
                $textstatus = 'Draft';
                break;
            case self::STATUS_OPEN:
                $textstatus = 'Open';
                break;
            case self::STATUS_EXPIRED:
                $textstatus = 'Expired';
                break;
            case self::STATUS_COMPLETED:
                $textstatus = 'Completed';
                break;
        }

        return $textstatus;
    }

    public function duplicate()
    {
        $company = $this->company;
        $cloned = $this->replicate();
        $cloned->nice_quote_id = $company->nicequoteid();
        $cloned->date = Carbon::now();
        $duedate = Carbon::now()->addDays($this->netdays)->toDateTimeString();
        $cloned->duedate = $duedate;
        $cloned->status = self::STATUS_DRAFT;
        $cloned->save();

        foreach ($this->items as $item) {
            $clonedrelation = $item->replicate();
            $clonedrelation->save();
            $cloned->items()->save($clonedrelation);
        }

        return $cloned;
    }

    public function generatePDFView()
    {
        $quote = $this;
        $pdf = PDF::loadView('pdf.quote', compact('quote'))
            ->setPaper('a4')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-right', '10mm')
            ->setOption('margin-left', '10mm');

        return $pdf;
    }

    public function scopeDraft($query)
    {
        return $query
            ->where('status', self::STATUS_DRAFT);
    }

    public function scopePending($query)
    {
        return $query
            ->where('status', self::STATUS_OPEN);
    }

    public function scopeExpired($query)
    {
        return $query
            ->where('status', self::STATUS_EXPIRED);
    }

    public function scopeArchived($query)
    {
        return $query
            ->where('archived', true);
    }

    public function scopeNotArchived($query)
    {
        return $query
            ->where('archived', false);
    }
}
