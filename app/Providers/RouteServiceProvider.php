<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });

        Route::model('recipient', 'App\Models\Recipient');
        Route::model('client', 'App\Models\Client');
        Route::model('company', 'App\Models\Company');
        Route::model('companyinvite', 'App\Models\CompanyInvite');
        Route::model('companyuserrequest', 'App\Models\CompanyUserRequest');
        Route::model('receipt', 'App\Models\Receipt');
        Route::model('invoice', 'App\Models\Invoice');
        Route::model('invoiceitem', 'App\Models\InvoiceItem');
        Route::model('oldinvoice', 'App\Models\OldInvoice');
        Route::model('oldinvoiceitem', 'App\Models\OldInvoiceItem');
        Route::model('itemtemplate', 'App\Models\ItemTemplate');
        Route::model('quote', 'App\Models\Quote');
        Route::model('quoteitem', 'App\Models\QuoteItem');
        Route::model('payment', 'App\Models\Payment');
        Route::model('role', 'App\Models\Role');
        Route::model('user', 'App\Models\User');

        Route::pattern('token', '([A-Za-z0-9-]+)');
        Route::pattern('recipient', '([A-Za-z0-9]+)');
        Route::pattern('client', '([A-Za-z0-9]+)');
        Route::pattern('company', '([A-Za-z0-9-.]+)');
        Route::pattern('companyinvite', '([A-Za-z0-9-]+)');
        Route::pattern('companyuserrequest', '([A-Za-z0-9]+)');
        Route::pattern('receipt', '([A-Za-z0-9]+)');
        Route::pattern('invoice', '([A-Za-z0-9]+)');
        Route::pattern('invoiceitem', '([A-Za-z0-9]+)');
        Route::pattern('oldinvoice', '([A-Za-z0-9]+)');
        Route::pattern('oldinvoiceitem', '([A-Za-z0-9]+)');
        Route::pattern('itemtemplate', '([A-Za-z0-9]+)');
        Route::pattern('quote', '([A-Za-z0-9]+)');
        Route::pattern('quoteitem', '([A-Za-z0-9]+)');
        Route::pattern('payment', '([A-Za-z0-9]+)');
        Route::pattern('permission', '([A-Za-z0-9]+)');
        Route::pattern('role', '([A-Za-z0-9-]+)');
        Route::pattern('user', '([A-Za-z0-9]+)');

        parent::boot();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
