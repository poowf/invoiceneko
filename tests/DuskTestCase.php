<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;
//    use DatabaseMigrations;

    /**
     *  Notes for Dusk.
     *
     * Remember to include the APP_URL in the VM/Container hosts file if a FQDN is being used
     *
     * Commenting out DatabaseMigrations drastically improves Dusk performance with the increased chance of conflicts
     * between unique values but good for testing
     *
     * ->clickLink('Text Here') does not get affected by CSS and is case sensitive for the actual text between <a> tags
     * ->press only works for buttons and does get affected by CSS e.g. text-transform: uppercase
     *
     * /
     *
     * /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     *
     * @return void
     */
    public static function prepare()
    {
        // static::useChromedriver('/usr/local/bin/chromedriver');
        // Use local chrome driver due to aarch64 not being supported yet
        // static::useChromedriver('/usr/bin/chromedriver');
        static::startChromeDriver();
    }

    /**
     * Set Up method to clear all browser cookies.
     */
    protected function setUp(): void
    {
        parent::setUp();
        foreach (static::$browsers as $browser) {
            session()->flush();
            $browser->driver->manage()->deleteAllCookies();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions())->addArguments([
            '--window-size=1024,768',
            '--disable-gpu',
            '--headless',
            '--no-sandbox',
        ]);

        if (env('USE_SELENIUM', 'false') == 'true') {
            return RemoteWebDriver::create(
                'http://selenium:4444/wd/hub', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
            );
        } else {
            return RemoteWebDriver::create(
                'http://localhost:9515', DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
            );
        }
    }
}
