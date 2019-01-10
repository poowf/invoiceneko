<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanyInvite;
use App\Models\User;
use App\Notifications\InviteUserNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Uuid;

class CompanyInviteController extends Controller
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
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        $roles = Bouncer::role()->all();

        return view('pages.company.invite.create', compact('roles'));
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
        foreach ($request->input('email') as $key => $invitee) {
            if ($invitee) {
                $companyInvite = new CompanyInvite();
                $companyInvite->email = $invitee;
                $companyInvite->token = Uuid::generate(4)->string;
                $companyInvite->expires_at = Carbon::now()->addDays(2);
                $companyInvite->roles = json_encode($request->input('roles')[$key]);
                $company->invites()->save($companyInvite);

                $companyInvite->notify(new InviteUserNotification($companyInvite));
            }
        }

        flash('Invite has been successfully sent to the user', 'success');

        return redirect()->route('company.users.index', ['company' => $company]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CompanyInvite $companyInvite
     *
     * @return \Illuminate\Http\Response
     */
    public function show(CompanyInvite $companyInvite)
    {
        $company = $companyInvite->company;
        $user = auth()->user();
        if ($company->hasUser($user)) {
            return redirect()->route('dashboard', ['company' => $company]);
        }

        return view('pages.company.invite.show', compact('companyInvite'));
    }

    /**
     * Process the specified resource.
     *
     * @param Request                   $request
     * @param \App\Models\CompanyInvite $companyInvite
     *
     * @throws \Exception
     *
     * @return void
     */
    public function join(Request $request, CompanyInvite $companyInvite)
    {
        $user = auth()->user();
        $now = Carbon::now();
        if (date_diff($now, $companyInvite->expires_at)->invert === 0) {
            $company = $companyInvite->company;
            $roles = json_decode($companyInvite->roles);

            $company->users()->attach($user->id);

            Bouncer::sync($user)->roles($roles);

            $companyInvite->delete();

            return redirect()->route('dashboard', ['company' => $company]);
        } else {
            return redirect()->route('dashboard');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CompanyInvite $companyInvite
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(CompanyInvite $companyInvite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param \App\Models\CompanyInvite $companyInvite
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CompanyInvite $companyInvite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CompanyInvite $companyInvite
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(CompanyInvite $companyInvite)
    {
        //
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param User    $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke(Request $request, Company $company, User $user)
    {
        if ($company->hasUser($user)) {
            Bouncer::sync($user)->roles([]);
            Bouncer::sync($user)->abilities([]);
            $company->users()->detach($user->id);
        }

        return redirect()->route('company.users.index', ['company' => $company]);
    }
}
