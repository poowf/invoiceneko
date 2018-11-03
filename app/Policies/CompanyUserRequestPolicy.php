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
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the companyUserRequest.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CompanyUserRequest  $companyUserRequest
     * @return mixed
     */
    public function view(User $user, CompanyUserRequest $companyUserRequest)
    {
        return $user->isOfCompanyUserRequest($companyUserRequest->id);
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the companyUserRequest.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CompanyUserRequest  $companyUserRequest
     * @return mixed
     */
    public function update(User $user, CompanyUserRequest $companyUserRequest)
    {
        return $companyUserRequest->isOwner($user);
    }

    /**
     * Determine whether the user can delete the companyUserRequest.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CompanyUserRequest  $companyUserRequest
     * @return mixed
     */
    public function delete(User $user, CompanyUserRequest $companyUserRequest)
    {
        return $companyUserRequest->isOwner($user);
    }
}
