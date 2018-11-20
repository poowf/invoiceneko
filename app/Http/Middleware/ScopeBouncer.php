<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Silber\Bouncer\Bouncer;

use Closure;

class ScopeBouncer
{
    /**
     * The Bouncer instance.
     *
     * @var \Silber\Bouncer\Bouncer
     */
    protected $bouncer;

    /**
     * Constructor.
     *
     * @param \Silber\Bouncer\Bouncer  $bouncer
     */
    public function __construct(Bouncer $bouncer)
    {
        $this->bouncer = $bouncer;
    }

    /**
     * Set the proper Bouncer scope for the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Here you may use whatever mechanism you use in your app
        // to determine the current tenant. To demonstrate, the
        // $tenantId is set here from the user's company_id.
        $companyId = ($request->user()) ? $request->user()->company_id : null;

        $this->bouncer->scope()->to($companyId);
        $this->bouncer->useRoleModel(Role::class);

        return $next($request);
    }
}
