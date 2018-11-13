<?php

namespace Tests\Browser;

use App\Models\Company;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class CompanySettingsTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_updating_company_settings()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();
        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $invoicePrefix = $faker->domainWord;
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/company/settings/edit')
                ->type('tax', $faker->numberBetween($min = 1, $max = 100))
                ->type('invoice_prefix', $invoicePrefix)
                ->type('quote_prefix', $faker->domainWord);
            $browser
                ->script('jQuery("#invoice_conditions").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->script('jQuery("#quote_conditions").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('UPDATE')
                ->assertPathIs('/company/settings/edit')
                ->assertPresent('#edit-company-settings')
                ->assertInputValue('invoice_prefix', $invoicePrefix);
        });
    }
}