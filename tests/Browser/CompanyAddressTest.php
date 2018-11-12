<?php

namespace Tests\Browser;

use App\Models\Company;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CompanyAddressTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_updating_company_address()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();
        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $streetName = $faker->streetName;
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/company/address/edit')
                ->type('block', $faker->buildingNumber)
                ->type('street', $streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->click('label[for="buildingtype-residential"]')
                ->press('UPDATE')
                ->assertPresent('#edit-address')
                ->assertInputValue('street', $streetName);
        });
    }
}