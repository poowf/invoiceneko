<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;
use Illuminate\Contracts\Auth\Guard;

class BindCompanyParameter
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
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->route()->hasParameter('company'))
        {
            $companyFQDN = $request->route()->parameter('company');

            $company = Company::where('domain_name', $companyFQDN)->firstOrFail();

            $request->route()->setParameter('company', $company);
        }
        return $next($request);
    }
}
