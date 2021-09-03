<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanyAddressRequest;
use App\Models\Company;
use App\Models\CompanyAddress;
use Illuminate\Http\Request;

class CompanyAddressController extends Controller
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
     * @param \App\Models\CompanyAddress $companyAddress
     *
     * @return void
     */
    public function show(Company $company, CompanyAddress $companyAddress)
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
            $companyaddress = $company->address;
        } else {
            $companyaddress = null;
        }

        return view('pages.company.address.edit', compact('companyaddress', 'company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanyAddressRequest $request
     * @param Company                     $company
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyAddressRequest $request, Company $company)
    {
        $companyaddress = $company->address;

        if (! $companyaddress) {
            $companyaddress = new CompanyAddress();
        }

        $companyaddress->fill($request->all());
        $company->address()->save($companyaddress);

        flash('Company Address Updated', 'success');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company                    $company
     * @param \App\Models\CompanyAddress $companyAddress
     *
     * @return void
     */
    public function destroy(Company $company, CompanyAddress $companyAddress)
    {
        //
    }
}
