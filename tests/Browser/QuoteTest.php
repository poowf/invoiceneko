<?php

namespace Tests\Browser;

use App\Models\Client;
use App\Models\ItemTemplate;
use App\Models\Quote;
use App\Models\QuoteItem;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class QuoteTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function test_creating_a_quote()
    {
        $client = Client::factory()->create();
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $client->company->id,
        ]);
        $company = $client->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $itemTemplate) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Create')
                ->assertPathIs('/' . $company->domain_name . '/quote/create')
                ->type('nice_quote_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#client_id").selectize()[0].selectize.setValue(' . $client->id . ');');
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser->press('CREATE')->assertPresent('#quote-action-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_adding_a_second_quote_item()
    {
        $client = Client::factory()->create();
        $itemTemplates = ItemTemplate::factory(5)->create([
            'company_id' => $client->company->id,
        ]);
        $company = $client->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $itemTemplates) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Create')
                ->assertPathIs('/' . $company->domain_name . '/quote/create')
                ->type('nice_quote_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#client_id").selectize()[0].selectize.setValue(' . $client->id . ');');
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplates[0]->name) . '");');
            $browser->click('a[id="quote-item-add"]');
            $browser->script('jQuery("#item_name_1").selectize()[0].selectize.setValue("' . addslashes($itemTemplates[1]->name) . '");');
            $browser->pause(2000);
            $browser->press('CREATE')->assertPresent('#quote-action-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_update_a_quote()
    {
        $client = Client::factory()->create();
        $company = $client->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $quote = Quote::factory()->create([
            'status' => Quote::STATUS_OPEN,
            'archived' => false,
            'client_id' => $client->id,
            'company_id' => $company->id,
        ]);
        $quoteItems = QuoteItem::factory(3)->create([
            'quote_id' => $quote->id,
        ]);
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();
        $salutation = ['mr', 'mrs', 'mdm', 'miss'];

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $quote) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes');
            $browser->script("jQuery(\"a[href='{$this->baseUrl()}/{$company->domain_name}/quote/{$quote->id}/edit'] > i\").click();");
            $browser
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#client_id").selectize()[0].selectize.setValue(' . $client->id . ');');
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

    public function test_delete_a_quote()
    {
        $client = Client::factory()->create();
        $company = $client->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $quote = Quote::factory()->create([
            'status' => Quote::STATUS_OPEN,
            'archived' => false,
            'client_id' => $client->id,
            'company_id' => $company->id,
        ]);
        $quoteItems = QuoteItem::factory(3)->create([
            'quote_id' => $quote->id,
        ]);

        $faker = Faker::create();
        $salutation = ['mr', 'mrs', 'mdm', 'miss'];

        $this->browse(function (Browser $browser) use ($company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes');
            $browser->script('jQuery(".quote-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#quote-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_end_to_end_quote()
    {
        $client = Client::factory()->create();
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $client->company->id,
        ]);
        $company = $client->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $itemTemplate) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Create')
                ->assertPathIs('/' . $company->domain_name . '/quote/create')
                ->type('nice_quote_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#client_id").selectize()[0].selectize.setValue(' . $client->id . ');');
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->assertPresent('#quote-action-container')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes');
            $browser->script("jQuery(\"a[data-tooltip='Edit Quote'] > i\").click();");
            $browser
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', $faker->bs())
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#client_id").selectize()[0].selectize.setValue(' . $client->id . ');');
            $browser->script(
                'jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");',
            );
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#quote-action-container')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes');
            $browser->script('jQuery(".quote-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#quote-container')
                ->assertPathBeginsWith('/' . $company->domain_name . '/quote');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
