<?php

namespace Tests\Browser;

use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class SignUpTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_creating_a_user_and_company()
    {
        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker) {
            $browser->visit('/user/create')
                ->type('username', str_random(10))
                ->type('email', $faker->unique()->safeEmail)
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->type('full_name', $faker->name)
                ->type('phone', '+659' . $faker->numberBetween($min = 0, $max = 8) . $faker->randomNumber(6, true))
                ->click('label[for="gender-male"]')
                ->press('NEXT')
                ->assertPathIs('/company/create')
                ->type('name', $faker->company)
                ->type('crn', $faker->ean8)
                ->type('domain_name', $faker->domainName)
                ->type('email', $faker->unique()->companyEmail)
                ->type('phone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true))
                ->attach('logo', __DIR__.'/assets/files/logo.png')
                ->attach('smlogo', __DIR__.'/assets/files/smlogo.png')
                ->press('CREATE')
                ->assertPathIs('/signin');
        });
    }
}
