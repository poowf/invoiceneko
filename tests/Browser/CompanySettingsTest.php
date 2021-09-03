<?php

namespace Tests\Browser;

use App\Models\Company;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CompanySettingsTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function test_updating_company_settings()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $invoicePrefix = $faker->domainWord;
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/company/settings/edit')
                ->type('tax', $faker->numberBetween($min = 1, $max = 100))
                ->type('invoice_prefix', $invoicePrefix)
                ->type('quote_prefix', $faker->domainWord);
            $browser
                ->script('jQuery("#invoice_conditions").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->script('jQuery("#quote_conditions").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('UPDATE')
                ->assertPathIs('/' . $company->domain_name . '/company/settings/edit')
                ->assertPresent('#edit-company-settings')
                ->assertInputValue('invoice_prefix', $invoicePrefix);
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
