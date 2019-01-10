<?php

namespace App\Models;

use Carbon\Carbon;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Contracts\Auditable;
use PDF;
use Uuid;

class Invoice extends Model implements Auditable
{
    public $generated = null;

    use \OwenIt\Auditing\Auditable;
    use SoftDeletes, CascadeSoftDeletes;
    use Notifiable;

    const STATUS_DRAFT = 1;
    const STATUS_OPEN = 2;
    const STATUS_CLOSED = 3;
    const STATUS_OVERDUE = 4;
    const STATUS_VOID = 5;
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
        'payment_complete_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $attributes = [
        'status' => self::STATUS_OPEN,
    ];

    protected $cascadeDeletes = [
        'items',
        'payments',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($invoice) {
            if ($invoice->status == self::STATUS_DRAFT && !$invoice->generated) {
                $invoice->status = self::STATUS_OPEN;
            }
            $date = clone $invoice->date;
            $invoice->duedate = $date->timezone(config('app.timezone'))->startOfDay()->addDays($invoice->netdays);
        });

        //Auto Increment of invoice_index per Company;
        static::created(function ($invoice) {
            $company = $invoice->company;
            $company->invoice_index = $company->invoice_index + 1;
            $company->save();
        });
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->client->recipients;
    }

    public function getTotalMoneyFormatAttribute()
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');

        return money_format('%!.2n', $this->total);
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

    public function getPaymentCompleteDateAttribute($value)
    {
        if (!is_null($value)) {
            $date = $this->asDateTime($value);

            return $date->timezone($this->company->timezone);
        }
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

    public function recurrence()
    {
        return $this->belongsTo('App\Models\InvoiceRecurrence', 'invoice_recurrence_id');
    }

    public function items()
    {
        return $this->hasMany('App\Models\InvoiceItem', 'invoice_id');
    }

    public function receipt()
    {
        return $this->hasOne('App\Models\Receipt', 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany('App\Models\Payment', 'invoice_id');
    }

    public function history()
    {
        return $this->hasMany(OldInvoice::class);
    }

    public function getClient()
    {
        return ($this->client) ? $this->client : (object) json_decode($this->client_data);
    }

    public function isLocked()
    {
        if (!is_null($this->payment_complete_date)) {
            $now = Carbon::now();
            if (date_diff($now, $this->payment_complete_date)->format('%a') >= '120') {
                return true;
            }
        }

        return false;
    }

    public function siblings()
    {
        if ($this->recurrence) {
            if ($this->recurrence->invoices) {
                return ($this->recurrence->invoices->except($this->id)->isNotEmpty()) ? $this->recurrence->invoices->except($this->id) : null;
            }
        }
    }

    public function hash()
    {
        $hash = hash('sha512', serialize($this.$this->items));

        return $hash;
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
            setlocale(LC_MONETARY, 'en_US.UTF-8');

            return money_format('%!.2n', $total);
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
            setlocale(LC_MONETARY, 'en_US.UTF-8');

            return money_format('%!.2n', $tax);
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
            setlocale(LC_MONETARY, 'en_US.UTF-8');

            return money_format('%!.2n', $total);
        } else {
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

        foreach ($payments as $payment) {
            $total -= $payment->amount;
        }

        return $total;
    }

    public function statusText()
    {
        $status = $this->status;

        switch ($status) {
            default:
                $textstatus = 'Pending';
                break;
            case self::STATUS_DRAFT:
                $textstatus = 'Draft';
                break;
            case self::STATUS_OPEN:
                $textstatus = 'Pending';
                break;
            case self::STATUS_OVERDUE:
                $textstatus = 'Overdue';
                break;
            case self::STATUS_CLOSED:
                $textstatus = 'Paid';
                break;
            case self::STATUS_WRITTENOFF:
                $textstatus = 'Written Off';
                break;
        }

        return $textstatus;
    }

    public function duplicate($date = null)
    {
        $date = ($date) ? $date : Carbon::now();

        $company = $this->company;
        $cloned = $this->replicate();
        $cloned->nice_invoice_id = $company->niceinvoiceid();
        $cloned->date = $date;
        $duedate = $date->addDays($this->netdays)->toDateTimeString();
        $cloned->duedate = $duedate;
        $cloned->status = self::STATUS_DRAFT;
        $cloned->invoice_recurrence_id = null;
        $cloned->generated = true;
        $cloned->save();

        foreach ($this->items as $item) {
            $clonedrelation = $item->replicate();
            $clonedrelation->save();
            $cloned->items()->save($clonedrelation);
        }

        $cloned->setInvoiceTotal();

        return $cloned;
    }

    public function generateShareToken($regenerate = false)
    {
        if ($regenerate) {
            $token = Uuid::generate(4)->string;
            $this->share_token = $token;
        } else {
            if ($this->share_token) {
                $token = $this->share_token;
            } else {
                $token = Uuid::generate(4)->string;
                $this->share_token = $token;
            }
        }

        $this->save();

        return $token;
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

    public function sendEmailNotification()
    {
        Mail::to($this->client->contactemail)
            ->cc($this->company->owner->email)
            ->send(new InvoiceMail($this));
    }

    public function scopeDateBetween($query, $startDate, $endDate)
    {
        return $query
            ->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeNotifiable($query)
    {
        return $query
            ->where('notify', true);
    }

    public function scopeOverdue($query)
    {
        $now = Carbon::now();

        return $query
            ->where('duedate', '<=', $now)
            ->whereIn('status', [self::STATUS_OPEN, self::STATUS_OVERDUE]);
    }

    public function scopePending($query)
    {
        return $query
            ->where('status', self::STATUS_OPEN);
    }

    public function scopeDraft($query)
    {
        return $query
            ->where('status', self::STATUS_DRAFT);
    }

    public function scopePaid($query)
    {
        return $query
            ->where('status', self::STATUS_CLOSED);
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
