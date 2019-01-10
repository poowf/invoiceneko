<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;

class DuskBrowserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Scrolls page to a specific element.
         *
         * Leaves a buffer at the top to account for a fixed header.
         */
        Browser::macro('scrollTo', function ($id) {
            $this->script("document.getElementById('$id').scrollIntoView()");
            $this->script('window.scroll(0, window.scrollY - 50)');

            return $this;
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
