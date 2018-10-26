<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

use Uuid;
use Log;
use PDF;
use Carbon\Carbon;

class Invoice extends Model
{
    use SoftDeletes, CascadeSoftDeletes;
    use Notifiable;

    const STATUS_DRAFT = 1;
    const STATUS_OPEN = 2;
    const STATUS_CLOSED = 3;
    const STATUS_OVERDUE = 4;
    const STATUS_VOID = 5;
    const STATUS_ARCHIVED = 6;
    const STATUS_WRITTENOFF = 7;

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
        'date',
        'duedate',
        'netdays',
    ];

    protected static function boot()
    {
        parent::boot();

        //Auto Creation of Settings per Company;
        static::created(function ($invoice) {
            $company = $invoice->company;
            $company->invoice_index = $company->invoice_index + 1;
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

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->client->contactemail;
    }

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
        return $this->hasMany('App\Models\InvoiceItem', 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'invoice_id');
    }

    public function history()
    {
        return $this->hasMany(OldInvoice::class);
    }

    public function owns($model)
    {
        return $this->id == $model->invoice_id;
    }

    public function calculatetotal($moneyformat = true)
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

    public function setInvoiceTotal()
    {
        $this->total = self::calculatetotal(false);
        $this->save();
    }

    public function calculateremainder()
    {
        $payments = $this->payments;
        $total = $this->total;

        foreach($payments as $payment)
        {
            $total -= $payment->amount;
        }

        return $total;
    }

    public function statusText()
    {
        $status = $this->status;

        switch($status)
        {
            default:
                $textstatus = "Pending";
            break;
            case self::STATUS_DRAFT:
                $textstatus = "Draft";
                break;
            case self::STATUS_OPEN:
                $textstatus = "Pending";
                break;
            case self::STATUS_OVERDUE:
                $textstatus = "Overdue";
                break;
            case self::STATUS_CLOSED:
                $textstatus = "Paid";
                break;
            case self::STATUS_ARCHIVED:
                $textstatus = "Archived";
                break;
            case self::STATUS_WRITTENOFF:
                $textstatus = "Written Off";
                break;
        }

        return $textstatus;
    }

    public function duplicate()
    {
        $company = $this->company;
        $cloned = $this->replicate();
        $cloned->nice_invoice_id = $company->niceinvoiceid();
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

    public function generateShareToken($regenerate = false)
    {
        if ($regenerate)
        {
            $token = Uuid::generate(4);
            $this->share_token = $token;
        }
        else
        {
            if($this->share_token)
            {
                $token = $this->share_token;
            }
            else
            {
                $token = Uuid::generate(4);
                $this->share_token = $token;
            }
        }

        $this->save();

        return $token;
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

    public function sendEmailNotification()
    {
        Mail::to($this->client->contactemail)
            ->cc($this->company->owner->email)
            ->send(new InvoiceMail($this));
    }

    public function scopeOverdue($query)
    {
        $now = Carbon::now();

        return $query
            ->where('duedate', '<=', $now)
            ->whereIn('status', [self::STATUS_OPEN, self::STATUS_OVERDUE]);
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

    public function scopePaid($query)
    {
        return $query
            ->where('status', self::STATUS_CLOSED);
    }

}
