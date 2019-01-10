<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function show()
    {
        return view('pages.signin');
    }

    public function multifactor_validate(Request $request)
    {
        return redirect()->intended();
    }

    public function username()
    {
        $username = request()->input('username');
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $username]);

        return $field;
    }

    public function redirectTo()
    {
        return Unicorn::redirectTo();
    }
}
