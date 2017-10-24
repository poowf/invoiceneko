<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmUserController extends Controller
{
    public function getConfirmEmail($token = null)
    {
        $user = User::where('confirmation_token', $token)->first();

        return view('pages.confirm', compact('token'));
    }

    public function postConfirmEmail(Request $request, $token = null)
    {
        if (!$token)
        {
            $token = $request->input('confirmation_token');
        }

        $user = User::where('confirmation_token', $token)->first();

        if ($user)
        {
            $user->confirmEmail();
            flash('Thanks for confirming your email! You may now sign in.', 'success');
            return redirect()->route('signin');
        }
        else
        {
            flash('Something went wrong, please request a new confirmation email', 'error');
            return redirect()->route('confirm.request');
        }

    }

    public function getRegenerateConfirmEmail()
    {
        return view('pages.confirmrequest');
    }

    public function postRegenerateConfirmEmail(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        $user->sendConfirmEmailNotification();

        flash('You will receive your confirmation email soon.', 'success');
        return redirect()->route('confirm');
    }
}
