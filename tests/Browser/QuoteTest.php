<?php

namespace Tests\Browser;

use Log;
use App\Models\Client;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class QuoteTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_creating_a_quote()
    {
        $client = factory(Client::class)->create();
        //Need to assign the company_id to the user
        $client->company->owner->company_id = $client->company->id;
        $client->company->owner->save();

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $client) {
            $browser->visit('/signin')
                ->type('username', $client->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/quotes')
                ->press('CREATE')
                ->type('nice_quote_id', $faker->slug)
                ->type('date', $faker->dateTime)
                ->type('client_id', $client->companyname)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name_0', $faker->realText($maxNbChars = 20, $indexSize = 1))
                ->type('item_quantity_0', $faker->numberBetween($min = 1, $max = 1000))
                ->type('item_price_0', $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL))
                ->press('CREATE')
                ->assertPresent('#quote-action-container');
        });
    }
}