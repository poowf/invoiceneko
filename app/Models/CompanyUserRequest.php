<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class CompanyUserRequest extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use Notifiable, SoftDeletes, CascadeSoftDeletes;

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_user_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
    ];

    protected $attributes = [
        'status' => self::STATUS_PENDING,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($companyuserrequest) {
            $companyuserrequest->token = Str::random(30);
        });
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function statusText()
    {
        $status = $this->status;

        switch ($status) {
            default:
                $textstatus = 'Pending';
                break;
            case self::STATUS_PENDING:
                $textstatus = 'Pending';
                break;
            case self::STATUS_APPROVED:
                $textstatus = 'Approved';
                break;
            case self::STATUS_REJECTED:
                $textstatus = 'Rejected';
                break;
        }

        return $textstatus;
    }
}
