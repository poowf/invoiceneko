<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyUserRequest;
use App\Http\Requests\UpdateCompanyUserRequest;
use App\Models\Company;
use App\Models\User;
use App\Notifications\NewCompanyUserNotification;
use DateTimeZone;
use PragmaRX\Countries\Package\Countries;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Illuminate\Http\Request;

class CompanyUserController extends Controller
{
    public function __construct(){
        $this->countries = new Countries();
    }

    /**
<<<<<<< da9089e6a7359021b663daf53d48fcb84ab9a1c0
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
=======
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Company $company)
>>>>>>> Refactor routes to use selected company data
    {
        if($company)
        {
            $users = $company->users()->paginate(12);
        }
        else
        {
            $users = collect();
        }

        return view('pages.company.users.index', compact('users', 'company'));
    }

    /**
<<<<<<< da9089e6a7359021b663daf53d48fcb84ab9a1c0
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
=======
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Company $company)
>>>>>>> Refactor routes to use selected company data
    {
        $countries = $this->countries->all();
        $timezones = \DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $roles = Bouncer::role()->all();

        return view('pages.company.users.create', compact('company', 'countries', 'timezones', 'roles'));
    }

    /**
     * @param CreateCompanyUserRequest $request
<<<<<<< da9089e6a7359021b663daf53d48fcb84ab9a1c0
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCompanyUserRequest $request)
=======
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateCompanyUserRequest $request, Company $company)
>>>>>>> Refactor routes to use selected company data
    {
        $random_password = str_random(16);

        $user = new User;
        $user->fill($request->all());
        $user->password = $random_password;
        $user->company_id = $company->id;
        $user->save();

        $roles = $request->input('roles');

        Bouncer::sync($user)->roles($roles);

        $user->notify(new NewCompanyUserNotification($user, $random_password));

        return redirect()->route('company.users.index');
    }

    /**
<<<<<<< da9089e6a7359021b663daf53d48fcb84ab9a1c0
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
=======
     * @param Company $company
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Company $company, User $user)
>>>>>>> Refactor routes to use selected company data
    {
        $countries = $this->countries->all();
        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $roles = Bouncer::role()->all();
        $userRoles = $user->getRoles();

        return view('pages.company.users.edit', compact('user', 'countries', 'timezones', 'roles', 'userRoles'));
    }

    /**
     * @param UpdateCompanyUserRequest $request
<<<<<<< da9089e6a7359021b663daf53d48fcb84ab9a1c0
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCompanyUserRequest $request, User $user)
=======
     * @param Company $company
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCompanyUserRequest $re
quest, Company $company, User $user)
>>>>>>> Refactor routes to use selected company data
    {
        $user->fill($request->all());
        if ($request->has('newpassword') && $request->input('newpassword') != null) {
            $newpass = $request->input('newpassword');
            $user->password = $newpass;
        }
        $user->save();

        $roles = $request->input('roles');

        Bouncer::sync($user)->roles($roles);

        return redirect()->back();
    }

    /**
     * @param Request $request
<<<<<<< da9089e6a7359021b663daf53d48fcb84ab9a1c0
=======
     * @param Company $company
>>>>>>> Refactor routes to use selected company data
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
<<<<<<< da9089e6a7359021b663daf53d48fcb84ab9a1c0
    public function destroy(Request $request, User $user)
=======
    public function destroy(Request $request, Company $company, User $user)
>>>>>>> Refactor routes to use selected company data
    {

        $auth_user = auth()->user();
        $usercompany = $company;

        //TODO: Probably need to rewrite/refactor this logic to somewhere else
        if ($usercompany)
        {
            if ($usercompany->isOwner($auth_user))
            {
                if($user->id != $auth_user->id)
                {
                    $user->delete();
                    flash('User Deleted', 'success');
                }
                else
                {
                    flash('You cannot delete the owner of the Company', 'error');
                }
            }
            else
            {
                flash('Unauthorised', 'error');
            }
        }
        else
        {
            flash('Nothing was done', 'error');
        }

        return redirect()->back();
    }
}
