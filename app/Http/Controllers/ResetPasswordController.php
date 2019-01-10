<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    public function show(Request $request, $token = null)
    {
        return view('pages.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    protected function resetPassword($user, $password)
    {
        //Override the method in ResetsPasswords Trait and
        //Remove Hash::make as the hashing is handled in the user model
        $user->password = $password;

        $user->setRememberToken(str_random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    public function redirectTo()
    {
        return Unicorn::redirectTo();
    }
}
