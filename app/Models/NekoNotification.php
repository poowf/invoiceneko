<?php

namespace App\Models;

use App\Library\Poowf\Unicorn;
use Illuminate\Notifications\DatabaseNotification;

class NekoNotification extends DatabaseNotification
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    public $incrementing = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'read_at',
    ];

    public function getCreatedAtAttribute($value)
    {
        $date = $this->asDateTime($value);

        return (auth()->user()) ? $date->timezone(auth()->user()->timezone) : $date->timezone(config('app.timezone'));
    }

    public function getReadAtAttribute($value)
    {
        $date = ($value) ? $this->asDateTime($value) : null;

        $timezone = (Unicorn::getCompanyKey()) ? Company::where('domain_name', Unicorn::getCompanyKey())->firstOrFail()->timezone : 'UTC';

        return ($date) ? $date->timezone($timezone) : null;
    }

}
