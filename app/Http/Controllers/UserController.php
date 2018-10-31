<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use Log;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.user.create');
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

    public function check(Request $request)
    {
        $email = $request->input('check_email');

//        $domain = preg_filter("/([^@]+)/","", $email);

        $explode = explode("@", $email);
        $domain = array_pop($explode);
        $company = Company::where('domain_name', $domain)->first();

        if($company)
        {
            return response()->json(['company_id' => $company->id]);
        }
        else
        {
            return abort(404);
        }

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
}
