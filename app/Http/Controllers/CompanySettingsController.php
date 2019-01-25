<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanySettingRequest;
use App\Models\Company;
use App\Models\CompanySetting;
use Illuminate\Http\Request;

class CompanySettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     *
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
     *
     * @return void
     */
    public function create(Company $company)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     *
     * @return void
     */
    public function store(Request $request, Company $company)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Company                    $company
     * @param \App\Models\CompanySetting $companySetting
     *
     * @return void
     */
    public function show(Company $company, CompanySetting $companySetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        if ($company) {
            $companySetting = $company->settings;
        } else {
            $companySetting = null;
        }

        return view('pages.company.settings.edit', compact('companySetting', 'company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanySettingRequest $request
     * @param Company                     $company
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanySettingRequest $request, Company $company)
    {
        $companySetting = $company->settings;

        if (!$companySetting) {
            $companySetting = new CompanySetting();
        }

        $companySetting->fill($request->all());
        $company->settings()->save($companySetting);

        flash('Company Settings Updated', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company                    $company
     * @param \App\Models\CompanySetting $companySetting
     *
     * @return void
     */
    public function destroy(Company $company, CompanySetting $companySetting)
    {
        //
    }
}
