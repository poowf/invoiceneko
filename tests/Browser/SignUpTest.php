<?php

namespace Tests\Browser;

use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class SignUpTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_creating_a_user_and_company()
    {
        $faker = Faker::create();
        $this->browse(function ($browser) use ($faker) {
            $browser->visit('/user/create')
                ->type('full_name', $faker->name)
                ->type('username', str_random(10))
                ->type('email', $faker->unique()->safeEmail)
                ->type('fphone', '+65' . $faker->randomNumber(8))
                ->click('label[for="gender-male"]')
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press('NEXT')
                ->assertPathIs('/company/create')
                ->type('name', $faker->company)
                ->type('crn', $faker->ean8)
                ->type('email', $faker->unique()->companyEmail)
                ->type('cphone', '+65' . $faker->randomNumber(8))
                ->press('CREATE')
                ->assertPathIs('/signin');
        });
    }
}
