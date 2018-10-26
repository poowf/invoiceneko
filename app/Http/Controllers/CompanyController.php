<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
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
        //
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
}
