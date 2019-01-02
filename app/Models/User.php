<?php

namespace App\Models;

use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasRolesAndAbilities, Notifiable, SoftDeletes, CascadeSoftDeletes;

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
        'phone',
        'gender',
        'country_code',
        'timezone',
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
        'timezone' => 'UTC',
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
     * Encrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setTwofaSecretAttribute($value)
    {
        $this->attributes['twofa_secret'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getTwofaSecretAttribute($value)
    {
        return ($value) ? decrypt($value) : null;
    }

    /**
     * Encrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function setTwofaBackupCodesAttribute($value)
    {
        $this->attributes['twofa_backup_codes'] = encrypt($value);
    }

    /**
     * Decrypt the user's google_2fa secret.
     *
     * @param  string  $value
     * @return string
     */
    public function getTwofaBackupCodesAttribute($value)
    {
        return ($value) ? decrypt($value) : null;
    }

    /**
     * Retrieve the users's gravatar logo
     *
     * @return string
     */
    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));

        return "//www.gravatar.com/avatar/$hash";
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
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
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

    public function getFirstCompanyKey()
    {
        return (is_null($this->companies->first())) ? null : $this->companies->first()->{(new Company)->getRouteKeyName()};
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class)->withTimestamps();
    }

    public function ownedCompanies()
    {
        return $this->hasMany('App\Models\Company', 'user_id');
    }
}
