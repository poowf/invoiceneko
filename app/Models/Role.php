<?php

namespace App\Models;

use \Silber\Bouncer\Database\Role as RoleBase;
use Silber\Bouncer\BouncerFacade as Bouncer;

class Role extends RoleBase
{

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'name';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        $user = auth()->user();
        $role = $this->where($this->getRouteKeyName(), $value)->where('scope', $user->company_id)->first();
        return $role;
    }
}