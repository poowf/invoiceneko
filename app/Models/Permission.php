<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes, CascadeSoftDeletes;

    protected $table = 'permissions';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'label',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }
}
