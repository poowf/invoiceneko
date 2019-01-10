<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Poowf\Otter\Otter;
use Poowf\Otter\OtterApplicationServiceProvider;

class OtterServiceProvider extends OtterApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Otter::night();

        parent::boot();
    }

    /**
     * Register the Otter gate.
     *
     * This gate determines who can access Otter in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewOtter', function ($user) {
            return in_array($user->email, [
                'zane@poowf.com',
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
