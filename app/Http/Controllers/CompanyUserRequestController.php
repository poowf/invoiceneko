<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUserRequest;
use App\Notifications\CompanyUserRequestApprovedNotification;
use App\Notifications\CompanyUserRequestRejectedNotification;
use App\Notifications\RequestCompanyAccessNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompanyUserRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        if($company)
        {
            $requests = $company->requests()->paginate(12);
        }
        else
        {
            $requests = collect();
        }

        return view('pages.company.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        return view('pages.company.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Company $company
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        $email = $request->input('email');

        $explode = explode("@", $email);
        $domain = array_pop($explode);
        $company = Company::where('domain_name', $domain)->first();

        if($company)
        {
            $companyuserrequest = new CompanyUserRequest;
            $companyuserrequest->fill($request->all());
            $company->requests()->save($companyuserrequest);

            $company->notify(new RequestCompanyAccessNotification($companyuserrequest->full_name, $companyuserrequest->email, $companyuserrequest->phone));

            flash('The request has been sent to the current owner of the Company', "success");

            return redirect()->route('main');
        }
        else
        {
            flash('Unable to find your company', "error");

            return redirect()->back();
        }
    }

    /**
     * @param CompanyUserRequest $request
     * @return RedirectResponse
     */
    public function approve(CompanyUserRequest $request)
    {
        $request->status = CompanyUserRequest::STATUS_APPROVED;
        $request->save();

        $request->notify(new CompanyUserRequestApprovedNotification($request->token));

        return redirect()->back();
    }

    /**
     * @param CompanyUserRequest $request
     * @return RedirectResponse
     */
    public function reject(CompanyUserRequest $request)
    {
        $request->status = CompanyUserRequest::STATUS_REJECTED;
        $request->save();

        $request->notify(new CompanyUserRequestRejectedNotification());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\CompanyUserRequest $request
     * @return void
     */
    public function show(CompanyUserRequest $request, Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company $company
     * @param  \App\Models\CompanyUserRequest $request
     * @return void
     */
    public function edit(CompanyUserRequest $request, Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CompanyUserRequest $request
     * @return void
     */
    public function update(CompanyUserRequest $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company $company
     * @param  \App\Models\CompanyUserRequest $request
     * @return void
     */
    public function destroy(CompanyUserRequest $request, Company $company)
    {
        //
    }
}
