<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CompanyUserRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyUserRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        if ($user->isAn('global-administrator')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->can('view-company-user-requests');
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-company-user-requests');
    }

    /**
     * Determine whether the user can update the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('update-company-user-requests');
    }

    /**
     * Determine whether the user can delete the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->can('delete-company-user-requests');
    }
}
