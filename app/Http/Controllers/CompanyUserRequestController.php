<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyUserRequest;
use App\Notifications\CompanyUserRequestApprovedNotification;
use App\Notifications\CompanyUserRequestRejectedNotification;
use App\Notifications\RequestCompanyAccessNotification;
use Illuminate\Http\Request;

class CompanyUserRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company = auth()->user()->company;

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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.company.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $email = $request->input('email');

        $explode = explode("@", $email);
        $domain = array_pop($explode);
        $company = Company::where('domain_name', $domain)->first();

        if($company)
        {
            $companyuserrequest = new CompanyUserRequest;
            $companyuserrequest->fill($request->all());
            $company->user_requests()->save($companyuserrequest);

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

    public function approve(Request $request, CompanyUserRequest $companyUserRequest)
    {
        $companyUserRequest->status = CompanyUserRequest::STATUS_APPROVED;
        $companyUserRequest->save();

        $companyUserRequest->notify(new CompanyUserRequestApprovedNotification($companyUserRequest->token));

        return redirect()->back();
    }

    public function reject(Request $request, CompanyUserRequest $companyUserRequest)
    {
        $companyUserRequest->status = CompanyUserRequest::STATUS_REJECTED;
        $companyUserRequest->save();

        $companyUserRequest->notify(new CompanyUserRequestRejectedNotification());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CompanyUserRequest  $companyUserRequest
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyUserRequest $companyUserRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CompanyUserRequest  $companyUserRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyUserRequest $companyUserRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CompanyUserRequest  $companyUserRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyUserRequest $companyUserRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CompanyUserRequest  $companyUserRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyUserRequest $companyUserRequest)
    {
        //
    }
}
