<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NekoNotification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    public $incrementing = false;
}
