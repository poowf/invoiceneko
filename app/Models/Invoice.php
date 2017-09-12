<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

use Carbon\Carbon;

class Invoice extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    const STATUS_UNPAID = 0;
    const STATUS_PAID = 1;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invoiceid',
        'date',
        'duedate',
        'netdays',
    ];

    protected $attributes = [
        'status' => self::STATUS_UNPAID
    ];

    protected $cascadeDeletes = [
        'items',
        'payments',
    ];

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
        return $this->hasMany('App\Models\InvoiceItem', 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'invoice_id');
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

    public function statusText()
    {
        $status = $this->status;

        switch($status)
        {
            default:
                $textstatus = "Unpaid";
            break;
            case 0:
                $textstatus = "Unpaid";
            break;
            case 1:
                $textstatus = "Paid";
            break;
        }

        return $textstatus;
    }

    public function scopeOverdue($query)
    {
        $now = Carbon::now();

        return $query
            ->where('duedate', '<=', $now)
            ->where('status', self::STATUS_UNPAID);
    }

}
