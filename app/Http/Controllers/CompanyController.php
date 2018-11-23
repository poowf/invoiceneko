<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyOwnerRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
use App\Models\Company;
use App\Models\User;
use Storage;
use Image;

class CompanyController extends Controller
{
    public function __construct(){
        $this->countries = new Countries();
    }

    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return void
     */
    public function index(Company $company)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        $ipLocation = geoip()->getLocation();

        if($ipLocation->country !== 'NekoCountry')
        {
            session(['_old_input.country_code' => $ipLocation->iso_code]);
        }

        if($ipLocation->timezone !== 'Asia/NekoCountry')
        {
            session(['_old_input.timezone' => $ipLocation->timezone]);
        }

        $countries = $this->countries->all();
        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        return view('pages.company.create', compact('countries', 'timezones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateCompanyRequest $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCompanyRequest $request, Company $company)
    {
        if ($request->session()->has('user_id') || auth()->check()) {
            $company = new Company;
            $company->fill($request->all());
            $company->user_id = ($request->session()->has('user_id')) ? $request->session()->pull('user_id') : auth()->user()->id;

            if(!is_null($request->input('country_code')) && is_null($request->input('timezone')))
            {
                if($request->has('timezone') && is_null($request->input('timezone')))
                {
                    $timezone = $this->countries
                        ->where('iso_3166_1_alpha2', $request->input('country_code'))
                        ->first()
                        ->hydrate('timezones')
                        ->timezones
                        ->first()
                        ->zone_name;
                    $company->timezone = $timezone;
                }
            }
            elseif (is_null($company->timezone))
            {
                $company->timezone = 'UTC';
            }
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
            $company->users()->attach($company->user_id);

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
     * @param Company $company
     * @return void
     */
    public function show(Company $company)
    {
        return view('pages.company.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $countries = $this->countries->all();
        $timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);

        return view('pages.company.edit', compact('company', 'countries', 'timezones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanyRequest $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $company->fill($request->all());
        if($request->has('country_code') && !is_null($request->input('country_code')))
        {
            if($request->has('timezone') && is_null($request->input('timezone')))
            {
                $timezone = $this->countries
                    ->where('iso_3166_1_alpha2', $request->input('country_code'))
                    ->first()
                    ->hydrate('timezones')
                    ->timezones
                    ->first()
                    ->zone_name;
                $company->timezone = $timezone;
            }
        }
        elseif (is_null($company->timezone))
        {
            $company->timezone = 'UTC';
        }
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

        flash('Company Updated', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @return void
     */
    public function destroy(Company $company)
    {
        //
    }

    public function edit_owner(Company $company) {

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

    /**
     * @param UpdateCompanyOwnerRequest $request
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update_owner(UpdateCompanyOwnerRequest $request, Company $company) {
        $user = User::find($request->input('user_id'));
        $company->user_id = $user->id;
        $company->save();

        return redirect()->back();
    }

    /**
     * @param Company $company
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_check(Company $company)
    {
        return view('pages.company.check');
    }

    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function check(Request $request, Company $company)
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

    /**
     * @param Request $request
     * @param Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switch(Request $request, Company $company)
    {
        $companyDomainName = $request->input('domain_name');

        session()->put('current_company_fqdn', $companyDomainName);

        return redirect()->route('dashboard', ['company' => $companyDomainName]);
    }
}
