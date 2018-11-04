<?php

namespace App\Models;

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

use PDF;
use Carbon\Carbon;

class Quote extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

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
        'duedate',
        'netdays',
    ];

    protected static function boot()
    {
        parent::boot();

        //Auto Creation of Settings per Company;
        static::created(function ($quote) {
            $company = $quote->company;
            $company->quote_index = $company->quote_index + 1;
            $company->save();
        });
    }

    protected $attributes = [
        'status' => self::STATUS_OPEN
    ];

    protected $cascadeDeletes = [
        'items',
        'payments',
    ];

    public function getTotalMoneyFormatAttribute()
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%!.2n', $this->total);
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

    public function owns($model)
    {
        return $this->id == $model->invoice_id;
    }

    public function calculatesubtotal($moneyformat = true)
    {
        $items = $this->items;
        $total = 0;

        foreach($items as $item)
        {
            $itemtotal = $item->quantity * $item->price;

            $total += $itemtotal;
        }

        if ($moneyformat)
        {
            setlocale(LC_MONETARY, 'en_US.UTF-8');
            return money_format('%!.2n', $total);
        }
        else
        {
            return $total;
        }
    }

    public function calculatetax($moneyformat = true)
    {
        $companysettings = $this->company->settings;
        $tax = 0;

        if($companysettings->tax && $companysettings->tax != 0)
        {
            $tax = $companysettings->tax;
        }

        $subtotal = $this->calculatesubtotal(false);

        $tax = ($subtotal * $tax)/100;

        if ($moneyformat)
        {
            setlocale(LC_MONETARY, 'en_US.UTF-8');
            return money_format('%!.2n', $tax);
        }
        else
        {
            return $tax;
        }
    }

    public function calculatetotal($moneyformat = true)
    {
        $companysettings = $this->company->settings;
        $tax = 0;

        if($companysettings->tax && $companysettings->tax != 0)
        {
            $tax = $companysettings->tax;
        }

        $subtotal = $this->calculatesubtotal(false);

        $total = ($subtotal * (100 + $tax))/100;

        if ($moneyformat)
        {
            setlocale(LC_MONETARY, 'en_US.UTF-8');
            return money_format('%!.2n', $total);
        }
        else
        {
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

        switch($status)
        {
            default:
                $textstatus = "Open";
                break;
            case self::STATUS_DRAFT:
                $textstatus = "Draft";
                break;
            case self::STATUS_OPEN:
                $textstatus = "Open";
                break;
            case self::STATUS_EXPIRED:
                $textstatus = "Expired";
                break;
            case self::STATUS_COMPLETED:
                $textstatus = "Completed";
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
        $duedate = Carbon::now()->addDays($this->netdays)->startOfDay()->toDateTimeString();
        $cloned->duedate = $duedate;
        $cloned->status = self::STATUS_DRAFT;
        $cloned->save();

        foreach($this->items as $item)
        {
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
            ->setOption('margin-bottom', '0mm')
            ->setOption('margin-top', '0mm')
            ->setOption('margin-right', '0mm')
            ->setOption('margin-left', '0mm')
            ->setOption('title', str_slug($this->nice_quote_id) . '.pdf');

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