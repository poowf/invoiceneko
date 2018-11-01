<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, CascadeSoftDeletes;

    use HasRoles;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;
    const STATUS_BANNED = 3;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'username',
        'email',
        'phone',
        'gender',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'twofa_secret',
        'twofa_timestamp'
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVE
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function setUsernameAttribute($username){

        $this->attributes['username'] = strtolower($username);
    }

    /**
     * Set the password attribute.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * Route notifications for the mail channel.
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function owns($model)
    {
        return $this->id == $model->user_id;
    }

    public function isOfCompany($company_id)
    {
        return $this->company_id == $company_id;
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-administrator');
    }

    public function isAdmin()
    {
        return $this->hasRole('administrator');
    }

    public function confirmEmail()
    {
        $this->confirmation_token = null;
        $this->save();
    }

    public function statusText()
    {
        $status = $this->status;

        switch($status)
        {
            default:
                $textstatus = "Unknown";
                break;
            case self::STATUS_ACTIVE:
                $textstatus = "Active";
                break;
            case self::STATUS_BANNED:
                $textstatus = "Banned";
                break;
            case self::STATUS_DISABLED:
                $textstatus = "Disabled";
                break;
        }

        return $textstatus;
    }

    public function sendConfirmEmailNotification()
    {
        $token = $this->confirmation_token;
        $this->notify(new ConfirmEmailNotification($token));
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function ownedcompany()
    {
        return $this->hasOne('App\Models\Company', 'user_id');
    }
}
