<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
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
        'status',
        'netdays',
        'client_id',
        'company_id',
    ];

    protected $attributes = [
        'status' => self::STATUS_OPEN
    ];

    protected $cascadeDeletes = [
        'olditems'
    ];

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

    public function calculatetotal()
    {
        $items = $this->items;

        $total = 0;

        foreach($items as $item)
        {
            $itemtotal = $item->quantity * $item->price;

            $total += $itemtotal;
        }
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        return money_format('%!.2n', $total);
    }

    public function generatePDFView()
    {
        $invoice = $this;
        $pdf = PDF::loadView('pdf.invoice', compact('invoice'))
            ->setPaper('a4')
            ->setOption('margin-bottom', '0mm')
            ->setOption('margin-top', '0mm')
            ->setOption('margin-right', '0mm')
            ->setOption('margin-left', '0mm');

        return $pdf;
    }
}
