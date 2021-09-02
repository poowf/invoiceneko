<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Silber\Bouncer\Database\Role as RoleBase;

class Role extends RoleBase implements Auditable
{
    use \OwenIt\Auditing\Auditable;

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
     * @param mixed $value
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = NULL)
    {
        $role = $this->where($this->getRouteKeyName(), $value)->where('scope', app('request')->route('company')->id)->first();

        return $role;
    }
}
