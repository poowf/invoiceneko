<?php

namespace Tests\Browser;

use App\Models\Company;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CompanyTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function test_updating_a_company()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/company/edit')
                ->type('name', $faker->company)
                ->type('crn', $faker->ean8)
                //TODO: Debug what's wrong with the doma_name input, dusk seems unable to find and use the input
                //                ->scrollTo('domain_name')
                //                ->type('domain_name', "invoiceneko.com")
                ->type('email', $faker->unique()->companyEmail)
                ->type('phone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true))
                ->press('UPDATE')
                ->assertPathIs('/' . $company->domain_name . '/company/edit')
                ->assertPresent('#edit-company');
            //                ->assertInputValue('domain_name', "invoiceneko.com");
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
