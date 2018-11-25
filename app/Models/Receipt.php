<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use PDF;
use OwenIt\Auditing\Contracts\Auditable;

class Receipt extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, CascadeSoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'receipts';

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

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoice_id');
    }

    public function generatePDFView()
    {
        $receipt = $this;
        $invoice = $this->invoice;
        $pdf = PDF::loadView('pdf.receipt', compact('invoice', 'receipt'))
            ->setPaper('a4')
            ->setOption('margin-bottom', '10mm')
            ->setOption('margin-top', '10mm')
            ->setOption('margin-right', '10mm')
            ->setOption('margin-left', '10mm');
        return $pdf;
    }
}
