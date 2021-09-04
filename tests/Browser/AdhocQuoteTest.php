<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\ItemTemplate;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdhocQuoteTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_creating_an_adhoc_quote()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/quote/adhoc/create')
                ->type('nice_quote_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#quote-action-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_updating_an_adhoc_quote()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/quote/adhoc/create')
                ->type('nice_quote_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#quote-action-container')
                ->clickLink('Edit')
                ->assertPathIs('/' . $company->domain_name . '/quote/adhoc/' . $company->quotes->first()->id . '/edit')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#quote-action-container')
                ->assertSee('The Turbo Ultra Turbonator');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_deleting_an_adhoc_quote()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/quote/adhoc/create')
                ->type('nice_quote_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#quote-action-container')
                ->clickLink('Delete');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#quote-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_end_to_end_adhoc_quote()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/quote/adhoc/create')
                ->type('nice_quote_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#quote-action-container')
                ->clickLink('Edit')
                ->assertPathIs('/' . $company->domain_name . '/quote/adhoc/' . $company->quotes->first()->id . '/edit')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#quote-action-container')
                ->assertSee('The Turbo Ultra Turbonator')
                ->assertPathIs('/' . $company->domain_name . '/quote/' . $company->quotes->first()->id);
            $browser->clickLink('Delete');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#quote-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
