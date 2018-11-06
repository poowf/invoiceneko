<?php

namespace Tests\Browser;

use App\Models\Company;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ClientTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_create_a_client()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $faker = Faker::create();
        $salutation = ["mr", "mrs", "mdm", "miss"];

        $this->browse(function (Browser $browser) use ($faker, $company, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/clients')
                ->click("a[href='{$this->baseUrl()}/client/create']")
                ->assertPathIs('/client/create')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('contactfirstname', $faker->firstName)
                ->type('contactemail', $faker->unique()->companyEmail)
                ->type('contactphone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6));
            $browser
                ->script('jQuery("#contactsalutation").selectize()[0].selectize.setValue("mr");');
            $browser
                ->press('CREATE')
                ->assertPresent('#client-container');
        });
    }
}