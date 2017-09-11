<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use Debugbar;
use File;
use Image;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->session()->has('user_id')) {
            Debugbar::info("fired");
            $company = new Company;
            $company->fill($request->all());
            $company->user_id = $request->session()->pull('user_id');
            $company->save();

            $storedirectory = '/perm_store/company/' . $company->id . '/photos/';

            if(!File::exists(public_path('/perm_store/company/' . $company->id . '/photos/'))) {
                File::makeDirectory(public_path('/perm_store/company/' . $company->id . '/photos/'), 0775, true);
            }

            if ($request->file('logo'))
            {
                $file = $request->file('logo');
                $uuid = str_random(25);
                $filename = $uuid . '.jpg';

                if (!file_exists(public_path($storedirectory . '/logo_' . $filename)))
                    Image::make($file)->save(public_path($storedirectory . '/logo_' . $filename));

                $filepath = $storedirectory . '/logo_' . $filename;

                $company->logo = $filepath;
            }

            if ($request->file('smlogo'))
            {
                $file = $request->file('smlogo');
                $uuid = str_random(25);
                $filename = $uuid . '.jpg';

                if (!file_exists(public_path($storedirectory . '/smlogo_' . $filename)))
                    Image::make($file)->save(public_path($storedirectory . '/smlogo_' . $filename));

                $filepath = $storedirectory . '/smlogo_' . $filename;

                $company->smlogo = $filepath;
            }

            $company->save();

            $user = User::find($company->user_id);
            $user->company_id = $company->id;
            $user->save();

            return redirect()->route('auth.show');
        }
        else
        {
            return redirect()->route('user.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
