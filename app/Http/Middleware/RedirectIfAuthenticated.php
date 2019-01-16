<?php

namespace App\Http\Middleware;

use App\Library\Poowf\Unicorn;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $routeKey = Unicorn::getCompanyKey();
            if ($routeKey) {
                return redirect()->route('dashboard', ['company' => $routeKey]);
            } else {
                return redirect()->route('nocompany');
            }
        }
        elseif ($request->session()->has('notice')) {
            $request->session()->forget('notice');
        }

        return $next($request);
    }
}
