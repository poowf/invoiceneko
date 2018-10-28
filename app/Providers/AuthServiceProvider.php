<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Client' => 'App\Policies\ClientPolicy',
        'App\Models\Company' => 'App\Policies\CompanyPolicy',
        'App\Models\InvoiceItem' => 'App\Policies\InvoiceItemPolicy',
        'App\Models\Invoice' => 'App\Policies\InvoicePolicy',
        'App\Models\OldInvoiceItem' => 'App\Policies\OldInvoiceItemPolicy',
        'App\Models\OldInvoice' => 'App\Policies\OldInvoicePolicy',
        'App\Models\ItemTemplate' => 'App\Policies\ItemTemplatePolicy',
        'App\Models\QuoteItem' => 'App\Policies\QuoteItemPolicy',
        'App\Models\Quote' => 'App\Policies\QuotePolicy',
        'App\Models\Payment' => 'App\Policies\PaymentPolicy',
        'App\Models\Permission' => 'App\Policies\PermissionPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


    }
}
