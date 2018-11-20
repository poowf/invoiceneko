<?php

namespace Tests\Browser;

use App\Models\Client;
use App\Models\Company;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class ClientTest extends DuskTestCase
{
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

        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $salutation = ["mr", "mrs", "mdm", "miss"];

        $this->browse(function (Browser $browser) use ($faker, $company, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Clients')
                ->assertPathIs('/' . $company->domain_name . '/clients')
                ->clickLink('Create')
                ->assertPathIs('/' . $company->domain_name . '/client/create')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('contactfirstname', $faker->firstName)
                ->type('contactemail', $faker->unique()->companyEmail)
                ->type('contactphone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true));
            $browser
                ->script('jQuery("#contactsalutation").selectize()[0].selectize.setValue("mr");');
            $browser
                ->press('CREATE')
                ->assertPresent('#client-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_update_a_client()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);

        $faker = Faker::create();
        $salutation = ["mr", "mrs", "mdm", "miss"];

        $this->browse(function (Browser $browser) use ($faker, $company, $client, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Clients')
                ->assertPathIs('/clients')
                ->click(".activator")
                ->pause('500')
                ->clickLink('Edit')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('contactfirstname', $faker->firstName)
                ->type('contactemail', $faker->unique()->companyEmail)
                ->type('contactphone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true));
            $browser
                ->script('jQuery("#contactsalutation").selectize()[0].selectize.setValue("mr");');
            $browser
                ->press('UPDATE')
                ->assertPathBeginsWith('/client')
                ->assertSee('Client Details');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_delete_a_client()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);

        $faker = Faker::create();
        $salutation = ["mr", "mrs", "mdm", "miss"];

        $this->browse(function (Browser $browser) use ($faker, $company, $client, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Clients')
                ->assertPathIs('/clients')
                ->click(".activator")
                ->pause(500)
                ->click('.client-delete-btn')
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#client-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_end_to_end_client()
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
                ->clickLink('Clients')
                ->assertPathIs('/clients')
                ->clickLink('Create')
                ->assertPathIs('/client/create')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('contactfirstname', $faker->firstName)
                ->type('contactemail', $faker->unique()->companyEmail)
                ->type('contactphone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true));
            $browser
                ->script('jQuery("#contactsalutation").selectize()[0].selectize.setValue("mr");');
            $browser
                ->press('CREATE')
                ->assertPresent('#client-container')
                ->click(".activator")
                ->pause('500')
                ->clickLink('Edit')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('contactfirstname', $faker->firstName)
                ->type('contactemail', $faker->unique()->companyEmail)
                ->type('contactphone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true));
            $browser
                ->script('jQuery("#contactsalutation").selectize()[0].selectize.setValue("mr");');
            $browser
                ->press('UPDATE')
                ->assertPathBeginsWith('/client')
                ->assertSee('Client Details')
                ->clickLink('Clients')
                ->assertPathIs('/clients')
                ->click(".activator")
                ->pause(500)
                ->click('.client-delete-btn')
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#client-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }
}