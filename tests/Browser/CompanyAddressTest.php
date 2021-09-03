<?php

namespace Tests\Browser;

use App\Models\Company;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CompanyAddressTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function test_updating_company_address()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $streetName = $faker->streetName;
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/'.$company->domain_name.'/dashboard')
                ->visit('/'.$company->domain_name.'/company/address/edit')
                ->type('block', $faker->buildingNumber)
                ->type('street', $streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->click('label[for="buildingtype-residential"]')
                ->press('UPDATE')
                ->assertPathIs('/'.$company->domain_name.'/company/address/edit')
                ->assertPresent('#edit-address')
                ->assertInputValue('street', $streetName);
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
