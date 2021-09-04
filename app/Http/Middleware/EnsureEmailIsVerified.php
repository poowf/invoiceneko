<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (!$request->user() || ($request->user() instanceof MustVerifyEmail && !$request->user()->hasVerifiedEmail())) {
            if (!$request->session()->has('notice')) {
                $request->session()->put('notice', [
                    // prettier-ignore
                    'message' => 'Verify your email address. Didn\'t receive a verification email? Click the Resend button to get a new one.',
                    'link.text' => 'Resend Verification Email',
                    'link' => route('verification.resend'),
                ]);
            } elseif ($request->session()->get('notice')['link.text'] !== 'Resend Verification Email') {
                $request->session()->put('notice', [
                    // prettier-ignore
                    'message' => 'Verify your email address. Didn\'t receive a verification email? Click the Resend button to get a new one.',
                    'link.text' => 'Resend Verification Email',
                    'link' => route('verification.resend'),
                ]);
            }
        } elseif ($request->user()->hasVerifiedEmail() && $request->session()->has('notice')) {
            $request->session()->forget('notice');
        }

        return $next($request);
    }
}
