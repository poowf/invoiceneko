<?php

namespace App\Http\Controllers;

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
            return redirect()->intended('/dashboard');
        }

        flash('Invalid Credentials', 'danger');
        return redirect()->back();
    }

    public function destroy()
    {
        Auth::logout();
        return redirect()->route('main');
    }
}
