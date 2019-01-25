<?php

namespace App\Http\Middleware;

use App\Library\Poowf\Unicorn;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class HasCompany
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $routeKey = Unicorn::getCompanyKey();
        if ($routeKey) {
            if ($request->route('company')->hasUser($request->user())) {
                return $next($request);
            } else {
                abort(401);
            }
        } else {
            return redirect()->route('nocompany');
        }
    }
}
