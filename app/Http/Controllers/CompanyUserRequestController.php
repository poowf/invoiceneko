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
     *
     * @return \Illuminate\Http\Response
     */
    public function index (Company $company)
    {
        if ($company) {
            $requests = $company->requests()->paginate(12);
        } else {
            $requests = collect();
        }

        return view('pages.company.requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function create (Company $company)
    {
        return view('pages.company.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     *
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request, Company $company)
    {
        $email = $request->input('email');

        $explode = explode('@', $email);
        $domain = array_pop($explode);
        $company = Company::where('domain_name', $domain)->first();

        if ($company) {
            $companyUserRequest = new CompanyUserRequest();
            $companyUserRequest->fill($request->all());
            $company->requests()->save($companyUserRequest);

            $company->notify(new RequestCompanyAccessNotification($companyUserRequest));

            flash('The request has been sent to the current owner of the Company', 'success');

            return redirect()->route('main');
        } else {
            flash('Unable to find your company', 'error');

            return redirect()->back();
        }
    }

    /**
     * @param CompanyUserRequest $companyUserRequest
     * @param Company            $company
     *
     * @return RedirectResponse
     */
    public function approve (Company $company, CompanyUserRequest $companyUserRequest)
    {
        $companyUserRequest->status = CompanyUserRequest::STATUS_APPROVED;
        $companyUserRequest->save();

        $companyUserRequest->notify(new CompanyUserRequestApprovedNotification($companyUserRequest->token));

        return redirect()->route('company.requests.index', ['company' => $company]);
    }

    /**
     * @param CompanyUserRequest $companyUserRequest
     * @param Company            $company
     *
     * @return RedirectResponse
     */
    public function reject (Company $company, CompanyUserRequest $companyUserRequest)
    {
        $companyUserRequest->status = CompanyUserRequest::STATUS_REJECTED;
        $companyUserRequest->save();

        $companyUserRequest->notify(new CompanyUserRequestRejectedNotification());

        return redirect()->route('company.requests.index', ['company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param Company                        $company
     * @param \App\Models\CompanyUserRequest $companyUserRequest
     *
     * @return void
     */
    public function show (Company $company, CompanyUserRequest $companyUserRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Company                        $company
     * @param \App\Models\CompanyUserRequest $companyUserRequest
     *
     * @return void
     */
    public function edit (Company $company, CompanyUserRequest $companyUserRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CompanyUserRequest $companyUserRequest
     *
     * @return void
     */
    public function update (CompanyUserRequest $companyUserRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Company                        $company
     * @param \App\Models\CompanyUserRequest $companyUserRequest
     *
     * @return void
     */
    public function destroy (Company $company, CompanyUserRequest $companyUserRequest)
    {
        //
    }
}
