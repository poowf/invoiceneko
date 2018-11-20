<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CompanyAddress;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyAddressPolicy
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
        return $user->can('view-company-address', CompanyAddress::class);
    }

    /**
     * Determine whether the user can view the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanyAddress $companyAddress
     * @return mixed
     */
    public function view(User $user, CompanyAddress $companyAddress)
    {
        return $user->can('view-company-address', $companyAddress);
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User $user
     * @param CompanyAddress $companyAddress
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-company-address', CompanyAddress::class);
    }

    /**
     * Determine whether the user can update the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanyAddress $companyAddress
     * @return mixed
     */
    public function update(User $user, CompanyAddress $companyAddress)
    {
        return $user->can('update-company-address', $companyAddress);
    }

    /**
     * Determine whether the user can delete the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanyAddress $companyAddress
     * @return mixed
     */
    public function delete(User $user, CompanyAddress $companyAddress)
    {
        return $user->can('delete-company-address', $companyAddress);
    }
}