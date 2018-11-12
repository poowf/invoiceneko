<?php

namespace Tests\Browser;

use App\Models\Company;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CompanyTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_updating_a_company()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/company/edit')
                ->type('name', $faker->company)
                ->type('crn', $faker->ean8)
                ->type('domain_name', "invoiceneko.com")
                ->type('email', $faker->unique()->companyEmail)
                ->type('phone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true))
                ->press('UPDATE')
                ->assertPresent('#edit-company')
                ->assertInputValue('domain_name', "invoiceneko.com");
        });
    }
}