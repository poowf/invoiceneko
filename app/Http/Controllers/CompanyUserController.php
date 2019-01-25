<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyUserRequest;
use App\Http\Requests\UpdateCompanyUserRequest;
use App\Models\Company;
use App\Models\User;
use App\Notifications\NewCompanyUserNotification;
use DateTimeZone;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use Silber\Bouncer\BouncerFacade as Bouncer;

class CompanyUserController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Company $company)
    {
        if ($company) {
            $users = $company->users()->paginate(12);
        } else {
            $users = collect();
        }

        return view('pages.company.users.index', compact('users', 'company'));
    }

    /**
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Company $company)
    {
        $countries = countries();
        $timezones = \DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $roles = Bouncer::role()->all();

        return view('pages.company.users.create', compact('company', 'countries', 'timezones', 'roles'));
    }

    /**
     * @param CreateCompanyUserRequest $request
     * @param Company                  $company
     *
     * @return \Illuminate\Http\RedirectResponse
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCompanyUserRequest $request, Company $company)
    {
        $random_password = str_random(16);

        $user = new User();
        $user->fill($request->all());
        $user->password = $random_password;
        $user->save();

        $company->users()->attach($user->id);

        $roles = $request->input('roles');

        Bouncer::sync($user)->roles($roles);

        $user->notify(new NewCompanyUserNotification($user, $random_password));

        return redirect()->route('company.users.index', ['company' => $company]);
    }

    /**
     * @param Company $company
     * @param User    $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Company $company, User $user)
    {
        $roles = Bouncer::role()->all();
        $userRoles = $user->getRoles();

        return view('pages.company.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * @param UpdateCompanyUserRequest $request
     * @param Company                  $company
     * @param User                     $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCompanyUserRequest $request, Company $company, User $user)
    {
        $roles = $request->input('roles');

        Bouncer::sync($user)->roles($roles);

        flash('User has been updated', 'success');

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param User    $user
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, Company $company, User $user)
    {
        $auth_user = auth()->user();

        //TODO: Probably need to rewrite/refactor this logic to somewhere else
        if ($company) {
            if ($company->isOwner($auth_user)) {
                if ($user->id != $auth_user->id) {
                    $user->delete();
                    flash('User Deleted', 'success');
                } else {
                    flash('You cannot delete the owner of the Company', 'error');
                }
            } else {
                flash('Unauthorised', 'error');
            }
        } else {
            flash('Nothing was done', 'error');
        }

        return redirect()->back();
    }
}
