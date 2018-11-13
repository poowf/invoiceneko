<?php

namespace Tests\Browser;

use App\Models\InvoiceItem;
use App\Models\ItemTemplate;
use App\Models\Invoice;
use Carbon\Carbon;
use Log;
use App\Models\Client;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class InvoiceTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_creating_an_invoice()
    {
        $client = factory(Client::class)->create();
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $client->company->id
        ]);

        //Need to assign the company_id to the user
        $client->company->owner->company_id = $client->company->id;
        $client->company->owner->save();

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $client, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $client->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Create')
                ->assertPathIs('/invoice/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#client_id").selectize()[0].selectize.setValue(1);');
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) .'");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->assertPresent('#invoice-action-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_adding_a_second_invoice_item()
    {
        $client = factory(Client::class)->create();
        $itemTemplates = factory(ItemTemplate::class, 5)->create([
            'company_id' => $client->company->id
        ]);

        //Need to assign the company_id to the user
        $client->company->owner->company_id = $client->company->id;
        $client->company->owner->save();

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $client, $itemTemplates) {
            $browser->visit('/signin')
                ->type('username', $client->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Create')
                ->assertPathIs('/invoice/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#client_id").selectize()[0].selectize.setValue(1);');
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplates[0]->name) .'");');
            $browser
                ->click('a[id="invoice-item-add"]');
            $browser
                ->script('jQuery("#item_name_1").selectize()[0].selectize.setValue("' . addslashes($itemTemplates[1]->name) .'");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->assertPresent('#invoice-action-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_update_a_invoice()
    {
        $client = factory(Client::class)->create();
        $company = $client->company;
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $invoice = factory(Invoice::class)->create([
            'status' => Invoice::STATUS_OPEN,
            'archived' => false,
            'client_id' => $client->id,
            'company_id' => $company->id
        ]);
        $invoiceItems = factory(InvoiceItem::class,3)->create([
            'invoice_id' => $invoice->id,
        ]);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id
        ]);

        $faker = Faker::create();
        $salutation = ["mr", "mrs", "mdm", "miss"];

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $invoice, $itemTemplate, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Pending')
                ->pause(500);
            $browser
                ->script("jQuery(\"a[href='{$this->baseUrl()}/invoice/{$invoice->id}/edit'] > i\").click();");
            $browser
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#client_id").selectize()[0].selectize.setValue(1);');
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#invoice-action-container')
                ->assertSee('The Turbo Ultra Turbonator');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_delete_a_invoice()
    {
        $client = factory(Client::class)->create();
        $company = $client->company;
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $invoice = factory(Invoice::class)->create([
            'status' => Invoice::STATUS_OPEN,
            'archived' => false,
            'client_id' => $client->id,
            'company_id' => $company->id
        ]);
        $invoiceItems = factory(InvoiceItem::class,3)->create([
            'invoice_id' => $invoice->id,
        ]);

        $faker = Faker::create();
        $salutation = ["mr", "mrs", "mdm", "miss"];

        $this->browse(function (Browser $browser) use ($faker, $company, $client, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Pending')
                ->pause(500);
            $browser
                ->script('jQuery(".invoice-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#invoice-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_invoice_log_payment()
    {
        $client = factory(Client::class)->create();
        $company = $client->company;
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $invoice = factory(Invoice::class)->create([
            'status' => Invoice::STATUS_OPEN,
            'archived' => false,
            'client_id' => $client->id,
            'company_id' => $company->id
        ]);
        $invoiceItems = factory(InvoiceItem::class,3)->create([
            'invoice_id' => $invoice->id,
        ]);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id
        ]);

        $faker = Faker::create();
        $salutation = ["mr", "mrs", "mdm", "miss"];

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $invoice, $itemTemplate, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Pending')
                ->pause(500);
            $browser
                ->script("jQuery(\"a[href='{$this->baseUrl()}/invoice/{$invoice->id}/edit'] > i\").click();");
            $browser
                ->assertPathBeginsWith('/invoice')
                ->clickLink('Log Payment')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser
                ->script('jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#invoice-action-container')
                ->assertPathBeginsWith('/invoice');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_end_to_end_invoice()
    {
        $client = factory(Client::class)->create();
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $client->company->id
        ]);

        //Need to assign the company_id to the user
        $client->company->owner->company_id = $client->company->id;
        $client->company->owner->save();

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $client, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $client->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Create')
                ->assertPathIs('/invoice/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#client_id").selectize()[0].selectize.setValue(1);');
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) .'");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->assertPresent('#invoice-action-container')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Pending')
                ->pause(500);
            $browser
                ->script("jQuery(\"a[data-tooltip='Edit Invoice'] > i\").click();");
            $browser
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', $faker->bs())
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#client_id").selectize()[0].selectize.setValue(1);');
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#invoice-action-container')
                ->clickLink('Invoices')
                ->assertPathIs('/invoices')
                ->clickLink('Pending')
                ->pause(500);
            $browser
                ->script('jQuery(".invoice-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#invoice-container')
                ->assertPathBeginsWith('/invoice');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }
}