<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\ItemTemplate;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AdhocInvoiceTest extends DuskTestCase
{
    /**
     * Creating an Adhoc Invoice.
     *
     * @throws \Throwable
     */
    public function test_creating_an_adhoc_invoice()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/' . $company->domain_name . '/invoices')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/invoice/adhoc/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#invoice-action-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    /**
     * Creating and Updating an Adhoc Invoice.
     *
     * @throws \Throwable
     */
    public function test_updating_an_adhoc_invoice()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/' . $company->domain_name . '/invoices')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/invoice/adhoc/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#invoice-action-container')
                ->clickLink('Edit')
                ->assertPathIs('/' . $company->domain_name . '/invoice/adhoc/' . $company->invoices->first()->id . '/edit')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#invoice-action-container')
                ->assertSee('The Turbo Ultra Turbonator')
                ->assertPathIs('/' . $company->domain_name . '/invoice/' . $company->invoices->first()->id);
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    /**
     * Creating and Deleting an Adhoc Invoice.
     *
     * @throws \Throwable
     */
    public function test_deleting_an_adhoc_invoice()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/' . $company->domain_name . '/invoices')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/invoice/adhoc/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#invoice-action-container')
                ->clickLink('Delete');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#invoice-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    /**
     * Creating and Logging a Payment for an Adhoc Invoice.
     *
     * @throws \Throwable
     */
    public function test_log_payment_on_an_adhoc_invoice()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/' . $company->domain_name . '/invoices')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/invoice/adhoc/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#invoice-action-container')
                ->clickLink('Log Payment')
                ->assertPathIs('/' . $company->domain_name . '/invoice/' . $company->invoices->first()->id . '/payment/create')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser
                ->script('jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#invoice-action-container')
                ->assertPathIs('/' . $company->domain_name . '/invoice/' . $company->invoices->first()->id);
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    /**
     * Creating, Updating, Logging a Payment and Deleting an Adhoc Invoice.
     *
     * @throws \Throwable
     */
    public function test_end_to_end_adhoc_invoice()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/' . $company->domain_name . '/invoices')
                ->clickLink('Create Ad-Hoc')
                ->assertPathIs('/' . $company->domain_name . '/invoice/adhoc/create')
                ->type('nice_invoice_id', substr($faker->slug, 0, 20) . 'sasdf')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#item_name_0").selectize()[0].selectize.setValue("' . addslashes($itemTemplate->name) . '");');
            $browser->pause(2000);
            $browser
                ->press('CREATE')
                ->pause(2000)
                ->assertPresent('#invoice-action-container')
                ->clickLink('Edit')
                ->assertPathIs('/' . $company->domain_name . '/invoice/adhoc/' . $company->invoices->first()->id . '/edit')
                ->type('companyname', $faker->company)
                ->type('block', $faker->buildingNumber)
                ->type('street', $faker->streetName)
                ->type('unitnumber', $faker->buildingNumber)
                ->type('postalcode', $faker->postcode)
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#invoice-action-container')
                ->assertSee('The Turbo Ultra Turbonator')
                ->assertPathIs('/' . $company->domain_name . '/invoice/' . $company->invoices->first()->id)
                ->clickLink('Log Payment')
                ->assertPathIs('/' . $company->domain_name . '/invoice/' . $company->invoices->first()->id . '/payment/create')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser
                ->script('jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#invoice-action-container')
                ->assertPathIs('/' . $company->domain_name . '/invoice/' . $company->invoices->first()->id);
            $browser
                ->clickLink('Delete');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#invoice-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
