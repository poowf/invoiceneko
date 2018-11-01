<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\CompanyUserRequest;
use Illuminate\Support\Facades\Hash;
use Log;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Google2FA;

class UserController extends Controller
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
     * @param Request $request
     * @param null $token
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $token = null;
        if ($request->query->has('token'))
        {
            $token = $request->query->get('token');
            $companyUserRequest = CompanyUserRequest::where('token', $token)->first();
            session(['_old_input.full_name' => $companyUserRequest->full_name]);
            session(['_old_input.email' => $companyUserRequest->email]);
            session(['_old_input.phone' => $companyUserRequest->phone]);
        }

        return view('pages.user.create', compact('token'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user = new User;
        $user->fill($request->all());
        $user->password = $request->input('password');
        $user->save();

        if ($request->query->has('token'))
        {
            $token = $request->query->get('token');
            $companyUserRequest = CompanyUserRequest::where('token', $token)->first();
            $user->company_id = $companyUserRequest->company_id;
            $user->save();

            $companyUserRequest->delete();

            session()->forget('_old_input.full_name');
            session()->forget('_old_input.email');
            session()->forget('_old_input.phone');

            flash('You can now sign in', 'success');

            return redirect()->route('auth.show');
        }

        $request->session()->put('user_id', $user->id);

        return redirect()->route('company.create');
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
        $user = auth()->user();
        return view('pages.user.edit', compact('user'));
    }

    /**
     * Retrieve the user and return as object
     *
     * @param  \App\Models\User $user
     * @return ItemTemplate
     */
    public function retrieve(User $user)
    {
        $auth_user = auth()->user();
        $usercompany = $user->company;

        //TODO: Probably need to rewrite/refactor this logic to somewhere else
        if ($usercompany)
        {
            if ($usercompany->isOwner($auth_user))
            {
                return response()->json($user);
            }
            else
            {
                return abort(401);
            }
        }
        else
        {
            return abort(401);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request)
    {
        $user = auth()->user();

        if (Hash::check($request->input('password'), $user->password)) {
            $user->fill($request->all());

            if ($request->has('newpassword') && $request->input('newpassword') != null) {
                $newpass = $request->input('newpassword');
                $user->password = $newpass;
            }

            if (!$user->save()) {
                flash('Failed to Update Profile', 'error');
                return redirect()->back();
            } else {
                flash('Successfully Updated Profile', 'success');
                return redirect()->back();
            }
        }

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

    public function security()
    {
        $user = auth()->user();

        return view('pages.user.security', compact('user'));
    }

    public function multifactor_start()
    {
        return redirect()->route('user.multifactor.create');
    }

    public function multifactor_create()
    {
        $user = auth()->user();
        $twofa_secret = Google2FA::generateSecretKey(32);

        session()->put('twofa_secret', $twofa_secret);

        $twoFactorUrl = Google2FA::getQRCodeUrl(
            'InvoicePlz',
            $user->email,
            $twofa_secret
        );

        return view('pages.user.multifactor.create', compact('twoFactorUrl', 'twofa_secret'));
    }

    public function multifactor_store(Request $request)
    {
        $multifactor_code = $request->input('multifactor_code');
        $twofa_secret = session()->pull('twofa_secret');
        $twofa_timestamp = Google2FA::getTimestamp();

        $valid = Google2FA::verifyKey($twofa_secret, $multifactor_code);

        if ($valid !== false) {
            $user = auth()->user();
            $user->twofa_secret = $twofa_secret;
            $user->twofa_timestamp = $twofa_timestamp;
            $user->save();

            flash("Two FA has been enabled for your account", 'success');
            return redirect()->route('user.security');
            // successful
        } else {
            // failed
            flash("Something went wrong, please try again", 'error');
            return redirect()->back();
        }

    }

    public function multifactor_destroy()
    {
        $user = auth()->user();
        $user->twofa_secret = null;
        $user->twofa_timestamp = null;
        $user->save();

        flash("Two FA has been disabled for your account", 'warning');
        return redirect()->back();
    }

    public function multifactor_verify(Request $request)
    {

//        $twofa_timestamp = Google2FA::getTimestamp();
//
//        $valid = Google2FA::verifyKey($twofa_secret, $multifactor_code);
//
//        if ($valid !== false) {
//            $user = auth()->user();
//            $user->twofa_secret = $twofa_secret;
//            $user->twofa_timestamp = $twofa_timestamp;
//            $user->save();
//
//            flash("Two FA has been enabled for your account", 'success');
//            return redirect()->route('user.security');
//            // successful
//        } else {
//            // failed
//            flash("Something went wrong, please try again", 'error');
//            return redirect()->back();
//        }
//        return $twoFactorUrl;

//        $user = auth()->user();
//
//        $multifactor_code = $request->input('multifactor_code');
//        $twofa_secret = $user->twofa_secret;
//        $twofa_timestamp = $user->twofa_timestamp;
//
//        $timestamp = Google2FA::verifyKeyNewer($twofa_secret, $multifactor_code, $twofa_timestamp);
//
//        if ($timestamp !== false) {
//            $user->twofa_timestamp = $timestamp;
//            $user->save();
//
//            return redirect()->intended();
//        } else {
//            // failed
//            flash("Something went wrong, please try again", 'error');
//            return redirect()->back();
//        }
//        return $twoFactorUrl;
    }
}
