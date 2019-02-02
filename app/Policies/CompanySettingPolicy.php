<?php

namespace App\Policies;

use App\Models\CompanySetting;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanySettingPolicy
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
    }

    public function index(User $user)
    {
        return $user->can('update-company-settings', CompanySetting::class);
    }

    /**
     * Determine whether the user can view the companySettings.
     *
     * @param \App\Models\User $user
     * @param CompanySetting   $companySettings
     *
     * @return mixed
     */
    public function view(User $user, CompanySetting $companySettings)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($companySettings->company_id) && $user->can('view-company-settings', $companySettings);
    }

    /**
     * Determine whether the user can create companies.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create-company-settings', CompanySetting::class);
    }

    /**
     * Determine whether the user can update the companySettings.
     *
     * @param \App\Models\User $user
     * @param CompanySetting   $companySettings
     *
     * @return mixed
     */
    public function update(User $user, CompanySetting $companySettings)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($companySettings->company_id) && $user->can('update-company-settings', $companySettings);
    }

    /**
     * Determine whether the user can delete the companySettings.
     *
     * @param \App\Models\User $user
     * @param CompanySetting   $companySettings
     *
     * @return mixed
     */
    public function delete(User $user, CompanySetting $companySettings)
    {
        $userCompanies = $user->companies()->pluck('companies.id');
        return $userCompanies->contains($companySettings->company_id) && $user->can('delete-company-settings', $companySettings);
    }
}
