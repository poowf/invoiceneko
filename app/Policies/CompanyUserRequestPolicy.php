<?php

namespace App\Policies;

use App\Models\CompanyAddress;
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

    public function index(User $user)
    {
        return $user->can('update-company-user-request', CompanyUserRequest::class);
    }

    /**
     * Determine whether the user can view the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanyUserRequest $companyUserRequest
     * @return mixed
     */
    public function view(User $user, CompanyUserRequest $companyUserRequest)
    {
        return $user->can('view-company-user-request', $companyUserRequest);
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-company-user-request', CompanyUserRequest::class);
    }

    /**
     * Determine whether the user can update the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanyUserRequest $companyUserRequest
     * @return mixed
     */
    public function update(User $user, CompanyUserRequest $companyUserRequest)
    {
        return $user->can('update-company-user-request', $companyUserRequest);
    }

    /**
     * Determine whether the user can delete the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanyUserRequest $companyUserRequest
     * @return mixed
     */
    public function delete(User $user, CompanyUserRequest $companyUserRequest)
    {
        return $user->can('delete-company-user-request', $companyUserRequest);
    }
}
