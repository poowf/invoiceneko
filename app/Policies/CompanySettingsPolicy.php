<?php

namespace App\Policies;

use App\Models\CompanyAddress;
use App\Models\User;
use App\Models\CompanySettings;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanySettingsPolicy
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
        return $user->can('view-company-settings', CompanySettings::class);
    }

    /**
     * Determine whether the user can view the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanySettings $companySettings
     * @return mixed
     */
    public function view(User $user, CompanySettings $companySettings)
    {
        return $user->can('view-company-settings', $companySettings);
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-company-settings', CompanySettings::class);
    }

    /**
     * Determine whether the user can update the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanySettings $companySettings
     * @return mixed
     */
    public function update(User $user, CompanySettings $companySettings)
    {
        return $user->can('update-company-settings', $companySettings);
    }

    /**
     * Determine whether the user can delete the companyUserRequest.
     *
     * @param  \App\Models\User $user
     * @param CompanySettings $companySettings
     * @return mixed
     */
    public function delete(User $user, CompanySettings $companySettings)
    {
        return $user->can('delete-company-settings', $companySettings);
    }
}