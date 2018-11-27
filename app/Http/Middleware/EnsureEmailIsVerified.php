<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                ! $request->user()->hasVerifiedEmail())) {

            $request->session()->put('notice', [
               'message' => 'Verify your email address. Didn\'t receive a verification email? Click the Resend button to get a new one.',
               'link.text' => 'Resend Verification Email',
               'link' => route('verification.resend')
            ]);
        }
        elseif($request->user()->hasVerifiedEmail() && $request->session()->has('notice'))
        {
            $request->session()->forget('notice');
        }

        return $next($request);
    }
}