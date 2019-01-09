<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
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
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
