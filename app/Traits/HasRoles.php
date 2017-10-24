<?php

namespace App\Traits;

use App\Models\Role;

trait HasRoles {
    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function assignRole($role)
    {
        return $this->roles()->save(
            Role::whereName($role)->firstOrFail()
        );
    }

    public function hasRole($role)
    {
        $role = $role ?: '';

        if (is_string($role))
        {
            return $this->roles->contains('name', $role);
        }

        /*        foreach ($role as $r)
                {
                    if ($this->hasRole($r->name))
                    {
                        return true;
                    }
                }*/

        return !! $role->intersect($this->roles)->count();
    }

    public function hasRoles($roles)
    {
        $roles = $roles ?: '';

        if (is_string($roles))
        {
            $roles = explode(',', $roles);
        }

        foreach($roles as $role)
        {
            if ($this->hasRole($role))
            {
                return true;
                break;
            }
        }
    }
}