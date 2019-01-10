<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class NekoSession extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sessions';

    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function getDeviceAttribute()
    {
        $agent = $this->setupAgent();
        $device = $agent->device();

        return $device;
    }

    public function getPlatformAttribute()
    {
        $agent = $this->setupAgent();
        $platform = $agent->platform();

        return $platform;
    }

    public function getBrowserAttribute()
    {
        $agent = $this->setupAgent();
        $browser = $agent->browser();

        return $browser;
    }

    public function isPhone()
    {
        $agent = $this->setupAgent();

        return $agent->isPhone();
    }

    public function getPlatformNameAttribute()
    {
        return "{$this->device} {$this->platform}, {$this->browser}";
    }

    public function getLastActivityAttribute($value)
    {
        return Carbon::createFromTimestamp($value)->format('d M Y');
    }

    public function setupAgent()
    {
        $agent = new Agent();
        $agent->setUserAgent($this->user_agent);

        return $agent;
    }
}
