<?php

namespace App\Http\Controllers;

use App\Library\Poowf\Unicorn;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    use VerifiesEmails;

    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect($this->redirectPath())
            : view('pages.verify');
    }

    public function redirectTo()
    {
        return Unicorn::redirectTo();
    }
}