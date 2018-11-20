<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use Log;
use Session;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class AuthController extends Controller
{
    public function show()
    {
        return view('pages.signin');
    }

    public function process(Request $request)
    {
        $remember = $request->has('remember') ? true : false;

        $field = filter_var($request->input("username"), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $creds = [
            $field => $request->input("username"),
            'password' => $request->input("password")
        ];

        if (Auth::attempt($creds, $remember)) {
            $routeKey = Unicorn::getCompanyKey();
            if($routeKey)
            {
                return redirect()->intended(route('dashboard', [ 'company' => $routeKey ]));
            }
            else
            {
                flash('Please fill in your company information first', 'error');
                return redirect()->route('company.create');
            }
        }

        flash('Invalid Credentials', 'error');
        return redirect()->back();
    }

    public function multifactor_validate(Request $request)
    {
        return redirect()->intended();
    }

    public function destroy()
    {
        session()->flush();
        Auth::logout();
        return redirect()->route('main');
    }
}
