<?php

namespace App\Http\Controllers;

use App\Traits\AuthHelper;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use AuthHelper;

    public function show(Request $request, $token = null)
    {
        return view('pages.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function process(Request $request)
    {
        $this->validate(
            $request,
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:6',
            ],
            []);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $request->only(
                'email', 'password', 'password_confirmation', 'token'
            ), function ($user, $password) {

            $user->forceFill([
                'password' => $password,
                'remember_token' => str_random(60),
            ])->save();

            Auth::guard()->login($user);
        }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
            ? redirect($this->redirectPath())
                ->with('status', trans($response))
            : redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => trans($response)]);
    }
}
