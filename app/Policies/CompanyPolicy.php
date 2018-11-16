<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Route;
use Log;

class CompanyPolicy
{
    use HandlesAuthorization;

    public function __construct()
    {
    }

    public function before($user, $ability)
    {
//        if ($user->isSuperAdmin()) {
//            return true;
//        }
    }

    /**
     * Determine whether the user can view the company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function view(User $user, Company $company)
    {
        return $user->isOfCompany($company->id);
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
     * Determine whether the user can update the company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function update(User $user, Company $company)
    {
        return $company->isOwner($user);
    }

    /**
     * Determine whether the user can delete the company.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Company  $company
     * @return mixed
     */
    public function delete(User $user, Company $company)
    {
        return $company->isOwner($user);
    }

    /**
     * Determine whether the user owns the company.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function owner(User $user)
    {
        $company = $user->company;
        if ($company)
        {
            return $company->isOwner($user);
        }
        else
        {
            //Check if the current route name matches company.edit or company.update and return true if it is
            return (Route::currentRouteName() === 'company.edit' || Route::currentRouteName() === 'company.update');
        }
    }
}
