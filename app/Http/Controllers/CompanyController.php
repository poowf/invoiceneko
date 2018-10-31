<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\CreateCompanyUserRequest;
use App\Http\Requests\UpdateCompanyOwnerRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Requests\UpdateCompanyUserRequest;
use App\Notifications\NewCompanyUserNotification;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Storage;
use Image;
use Log;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCompanyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCompanyRequest $request)
    {

        if ($request->session()->has('user_id')) {
            $company = new Company;
            $company->fill($request->all());
            $company->user_id = $request->session()->pull('user_id');
            $company->save();

            $storedirectory = '/perm_store/company/' . $company->id . '/photos/';

            Storage::makeDirectory($storedirectory);

            if ($request->file('logo'))
            {
                $file = $request->file('logo');
                $uuid = str_random(25);
                $filename = $uuid . '.png';

                if (!Storage::exists($storedirectory . 'logo_' . $filename))
                {
                    $image = Image::make($file)->fit(420, 220, function ($constraint) {
                        $constraint->upsize();
                    }, 'center');
                    Storage::put($storedirectory . 'logo_' . $filename, $image->stream('jpg')->detach());
                }

                $filepath = $storedirectory . 'logo_' . $filename;

                $company->logo = $filepath;
            }

            if ($request->file('smlogo'))
            {
                $file = $request->file('smlogo');
                $uuid = str_random(25);
                $filename = $uuid . '.png';

                if (!Storage::exists($storedirectory . 'smlogo_' . $filename))
                {
                    $image = Image::make($file)->fit(200, 200, function ($constraint) {
                        $constraint->upsize();
                    }, 'center');
                    Storage::put($storedirectory . 'smlogo_' . $filename, $image->stream('jpg')->detach());
                }

                $filepath = $storedirectory . 'smlogo_' . $filename;

                $company->smlogo = $filepath;
            }

            $company->save();

            $user = User::find($company->user_id);
            $user->company_id = $company->id;
            $user->save();

            flash('You can now sign in', 'success');

            return redirect()->route('auth.show');
        }
        else
        {
            flash('Something went wrong', 'error');

            return redirect()->route('user.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @return void
     */
    public function show()
    {
        $company = auth()->user()->company;
        return view('pages.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $company = auth()->user()->ownedcompany;
        return view('pages.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request)
    {
        $isnew = false;

        if (auth()->user()->ownedcompany)
        {
            $company = auth()->user()->ownedcompany;
        }
        else
        {
            //TODO: Prevent User from registering a company if the domain name has already been registered.
            $company = new Company;
            $company->user_id = auth()->user()->id;
            $isnew = true;
        }

        $company->fill($request->all());
        $company->save();

        $storedirectory = '/perm_store/company/' . $company->id . '/photos/';
        Storage::makeDirectory($storedirectory);

        if ($request->file('logo'))
        {
            $file = $request->file('logo');
            $uuid = str_random(25);
            $filename = $uuid . '.png';

            if (!Storage::exists($storedirectory . 'logo_' . $filename))
            {
                $image = Image::make($file)
                    ->encode('png', 100)
                    ->fit(420, 220, function ($constraint) {
                    $constraint->upsize();
                }, 'center');
                Storage::put($storedirectory . 'logo_' . $filename, $image->stream('jpg')->detach());
            }

            $filepath = $storedirectory . 'logo_' . $filename;

            $company->logo = $filepath;
        }

        if ($request->file('smlogo'))
        {
            $file = $request->file('smlogo');
            $uuid = str_random(25);
            $filename = $uuid . '.png';

            if (!Storage::exists($storedirectory . 'smlogo_' . $filename))
            {
                $image = Image::make($file)
                    ->encode('png', 100)
                    ->fit(200, 200, function ($constraint) {
                    $constraint->upsize();
                }, 'center');
                Storage::put($storedirectory . 'smlogo_' . $filename, $image->stream('jpg')->detach());
            }

            $filepath = $storedirectory . 'smlogo_' . $filename;

            $company->smlogo = $filepath;
        }

        $company->save();

        if ($isnew)
        {
            $user = User::find($company->user_id);
            $user->company_id = $company->id;
            $user->save();
        }

        flash('Company Updated', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return void
     */
    public function destroy()
    {
        //
    }

    public function edit_owner() {
        $company = auth()->user()->company;

        if($company)
        {
            $owner = $company->owner;
            $users = $company->users;
        }
        else
        {
            $owner = collect();
            $users = collect();
        }

        return view('pages.company.owner.edit', compact('company', 'owner', 'users'));
    }

    public function update_owner(UpdateCompanyOwnerRequest $request) {
        $company = auth()->user()->ownedcompany;
        $user = User::find($request->input('user_id'));
        $company->user_id = $user->id;
        $company->save();

        return redirect()->back();
    }

    public function index_users() {
        $company = auth()->user()->company;

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

    public function create_users() {
        $company = auth()->user()->company;

        return view('pages.company.users.create', compact('company'));
    }
    public function store_users(CreateCompanyUserRequest $request) {
        $company = auth()->user()->company;

        $random_password = str_random(16);

        $user = new User;
        $user->fill($request->all());
        $user->password = $random_password;
        $user->company_id = $company->id;
        $user->save();

        $user->notify(new NewCompanyUserNotification($user, $random_password));

        return redirect()->back();
    }

    public function edit_users(User $user) {
        return view('pages.company.users.edit', compact('user'));
    }

    public function update_users(UpdateCompanyUserRequest $request, User $user) {
        $user->fill($request->all());
        if ($request->has('newpassword') && $request->input('newpassword') != null) {
            $newpass = $request->input('newpassword');
            $user->password = $newpass;
        }
        $user->save();

        return redirect()->back();
    }

    public function destroy_users(Request $request, User $user) {

        $auth_user = auth()->user();
        $usercompany = $user->company;

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

    public function show_check()
    {
        return view('pages.company.check');
    }

    public function check(Request $request)
    {
        $email = $request->input('email');

//        $domain = preg_filter("/([^@]+)/","", $email);

        $explode = explode("@", $email);
        $domain = array_pop($explode);
        $company = Company::where('domain_name', $domain)->first();

        if($company)
        {
            return redirect()->route('company.requests.create');
        }
        else
        {
            return redirect()->route('user.create');
        }
    }
}
