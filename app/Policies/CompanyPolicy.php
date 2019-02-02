<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
        return $user->can('view-company', Company::class);
    }

    /**
     * Determine whether the user can view the company.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Company $company
     *
     * @return mixed
     */
    public function view(User $user, Company $company)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($company->id) && $user->can('view-company', $company);
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
        //
    }

    /**
     * Determine whether the user can update the company.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Company $company
     *
     * @return mixed
     */
    public function update(User $user, Company $company)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($company->id) && $user->can('update-company', $company);
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param \App\Models\User    $user
     * @param \App\Models\Company $company
     *
     * @return mixed
     */
    public function delete(User $user, Company $company)
    {
        if ($company) {
            return $company->isOwner($user);
        }
    }

    /**
     * Determine whether the user owns the company.
     *
     * @param \App\Models\User $user
     * @param Company          $company
     *
     * @return mixed
     */
    public function owner(User $user, Company $company)
    {
        if ($company) {
            return $company->isOwner($user);
        }
    }

    /**
     * Determine whether the user owns the company.
     *
     * @param \App\Models\User $user
     * @param Company          $company
     *
     * @return mixed
     */
    public function member(User $user, Company $company)
    {
        if ($company) {
            return $company->hasUser($user);
        }
    }

    /**
     * Determine whether the user can update the companySettings.
     *
     * @param \App\Models\User $user
     * @param Company          $company
     *
     * @return mixed
     */
    public function settings(User $user, Company $company)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($company->id) && $user->can('update-company-settings', $company->settings);
    }

    /**
     * Determine whether the user can update the companyAddress.
     *
     * @param \App\Models\User $user
     * @param Company          $company
     *
     * @return mixed
     */
    public function address(User $user, Company $company)
    {
        $userCompanies = $user->companies()->pluck('companies.id');

        return $userCompanies->contains($company->id) && $user->can('update-company-address', $company->address);
    }
}
