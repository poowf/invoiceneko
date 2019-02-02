<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Silber\Bouncer\Database\Role;

class RolePolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function before($user, $ability)
    {
    }

    public function index(User $user)
    {
        return $user->can('view-role', Role::class);
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param \App\Models\User $user
     * @param Role             $role
     *
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($role->scope) && $user->can('view-role', $role);
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-role', Role::class);
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param \App\Models\User $user
     * @param Role             $role
     *
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($role->scope) && $user->can('update-role', $role);
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param \App\Models\User $user
     * @param Role             $role
     *
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($role->scope) && $user->can('delete-role', $role);
    }
}
