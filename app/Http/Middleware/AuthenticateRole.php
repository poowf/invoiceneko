<?php
namespace App\Http\Middleware;

use Closure;
use Log;
use Illuminate\Contracts\Auth\Guard;

class AuthenticateRole
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
     * @param  Guard  $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param array ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {

        if($request->user()->isSuperAdmin())
        {
            return $next($request);
        }

        if (!$request->user()->hasRoles($roles)) {
            return redirect()->route('main');
        }

        return $next($request);
    }
}
