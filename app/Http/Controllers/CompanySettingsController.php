<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanySettingsRequest;
use App\Models\Company;
use App\Models\CompanySettings;
use Illuminate\Http\Request;

class CompanySettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return void
     */
    public function index(Company $company)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     * @return void
     */
    public function create(Company $company)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Company $company
     * @return void
     */
    public function store(Request $request, Company $company)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\CompanySettings $companySettings
     * @return void
     */
    public function show(Company $company, CompanySettings $companySettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        $ownedcompany = $company;

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
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanySettingsRequest $request, Company $company)
    {
        $ownedcompany = auth()->user()->ownedcompany;
        $companysettings = $ownedcompany->settings;

        if(!$companysettings)
        {
            $companysettings = new CompanySettings;
        }

        $companysettings->fill($request->all());
        $ownedcompany->settings()->save($companysettings);

        flash('Company Settings Updated', 'success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param  \App\Models\CompanySettings $companySettings
     * @return void
     */
    public function destroy(Company $company, CompanySettings $companySettings)
    {
        //
    }
}
