<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PDF;

class OldInvoice extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    const STATUS_OPEN = 0;
    const STATUS_CLOSED = 1;
    const STATUS_VOID = 5;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'old_invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nice_invoice_id',
        'date',
        'duedate',
        'total',
        'status',
        'netdays',
        'client_data',
        'client_id',
        'company_id',
    ];

    protected $attributes = [
        'status' => self::STATUS_OPEN,
    ];

    protected $cascadeDeletes = [
        'olditems',
    ];

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

        return (auth()->user()) ? $date->timezone(auth()->user()->timezone) : $date->timezone(config('app.timezone'));
    }

    public function getDuedateAttribute($value)
    {
        $date = $this->asDateTime($value);

        return (auth()->user()) ? $date->timezone(auth()->user()->timezone) : $date->timezone(config('app.timezone'));
    }

    public function items()
    {
        return $this->hasMany('App\Models\OldInvoiceItem', 'old_invoice_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'client_id');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function current_invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }

    public function getClient()
    {
        return ($this->client) ? $this->client : (object) json_decode($this->client_data);
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
            $amount = new \NumberFormatter( 'en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, "* #####.00 ;(* #####.00)");

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
            $amount = new \NumberFormatter( 'en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, "* #####.00 ;(* #####.00)");

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
            $amount = new \NumberFormatter( 'en_US.UTF-8', \NumberFormatter::PATTERN_DECIMAL, "* #####.00 ;(* #####.00)");

            return $amount->format($total);
        } else {
            return $total;
        }
    }

    public function setInvoiceTotal()
    {
        $this->total = self::calculatetotal(false);
        $this->save();
    }

    public function generatePDFView()
    {
        $invoice = $this;
        $client = $this->getClient();

        $pdf = PDF::loadView('pdf.invoice', compact('invoice', 'client'))
            ->setPaper('a4')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-right', '10mm')
            ->setOption('margin-left', '10mm');

        return $pdf;
    }
}
