<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanySettingsRequest;
use App\Models\CompanySettings;
use Illuminate\Http\Request;

class CompanySettingsController extends Controller
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
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanySettings $companySettings
     * @return void
     */
    public function show(CompanySettings $companySettings)
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
        $ownedcompany = auth()->user()->ownedcompany;

        if($ownedcompany)
        {
            $companysettings = $ownedcompany->settings;
        }
        else
        {
            $companysettings = null;
        }


        return view('pages.company.settings.edit', compact('companysettings', 'ownedcompany'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanySettingsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanySettingsRequest $request)
    {
        $ownedcompany = auth()->user()->ownedcompany;
        $companysettings = $ownedcompany->settings;

        if(!$companysettings)
        {
            $companysettings = new CompanySettings;
        }

        $companysettings->fill($request->all());
        $ownedcompany->settings()->save($companysettings);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanySettings $companySettings
     * @return void
     */
    public function destroy(CompanySettings $companySettings)
    {
        //
    }
}
