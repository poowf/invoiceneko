<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing', 'staging', 'dusk')) {
            $this->app->register(DuskServiceProvider::class);
            $this->app->register(DuskBrowserServiceProvider::class);
            $this->app->register(IdeHelperServiceProvider::class);
        }

        if ($this->app->environment('production', 'local', 'testing', 'staging')) {
            $this->app->register(HorizonServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        Bouncer::runAfterPolicies();
    }
}
