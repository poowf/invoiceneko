<?php

namespace Tests\Browser;

use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\ItemTemplate;
use App\Models\Quote;
use App\Models\QuoteItem;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PolicyTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_accessing_another_company_invoice()
    {
        $client = factory(Client::class)->create();
        $company = $client->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $invoice = factory(Invoice::class)->create([
            'status'     => Invoice::STATUS_OPEN,
            'archived'   => false,
            'client_id'  => $client->id,
            'company_id' => $company->id,
        ]);
        $invoiceItems = factory(InvoiceItem::class, 3)->create([
            'invoice_id' => $invoice->id,
        ]);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();
        $salutation = ['mr', 'mrs', 'mdm', 'miss'];

        $secondCompany = factory(Company::class)->create();
        $secondCompany->users()->attach($secondCompany->user_id);

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $secondCompany, $invoice, $itemTemplate, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Invoices')
                ->assertPathIs('/' . $company->domain_name . '/invoices')
                ->clickLink('Pending')
                ->pause(500);
            $browser
                ->script("jQuery(\"a[href='{$this->baseUrl()}/{$company->domain_name}/invoice/{$invoice->id}/edit'] > i\").click();");
            $browser
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#client_id").selectize()[0].selectize.setValue(' . $client->id . ');');
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#invoice-action-container')
                ->assertSee('The Turbo Ultra Turbonator');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
            $browser->visit('/signin')
                ->type('username', $secondCompany->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $secondCompany->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/invoice/' . $invoice->id)
                ->assertSee('Error 401')
                ->screenshot('view-another-company-invoice-test')
                ->visit('/')
                ->assertPathIs('/' . $secondCompany->domain_name . '/dashboard');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_accessing_another_company_quote()
    {
        $client = factory(Client::class)->create();
        $company = $client->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $quote = factory(Quote::class)->create([
            'status'     => Quote::STATUS_OPEN,
            'archived'   => false,
            'client_id'  => $client->id,
            'company_id' => $company->id,
        ]);
        $quoteItems = factory(QuoteItem::class, 3)->create([
            'quote_id' => $quote->id,
        ]);
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id,
        ]);

        $faker = Faker::create();
        $salutation = ['mr', 'mrs', 'mdm', 'miss'];

        $secondCompany = factory(Company::class)->create();
        $secondCompany->users()->attach($secondCompany->user_id);

        $this->browse(function (Browser $browser) use ($faker, $client, $company, $secondCompany, $quote, $itemTemplate, $salutation) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Quotes')
                ->assertPathIs('/' . $company->domain_name . '/quotes')
                ->clickLink('Pending')
                ->pause(500);
            $browser
                ->script("jQuery(\"a[href='{$this->baseUrl()}/{$company->domain_name}/quote/{$quote->id}/edit'] > i\").click();");
            $browser
                ->type('netdays', $faker->numberBetween($min = 1, $max = 60))
                ->type('item_name[]', 'The Turbo Ultra Turbonator')
                ->type('item_quantity[]', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('item_price[]', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#client_id").selectize()[0].selectize.setValue(' . $client->id . ');');
            $browser
                ->script('jQuery("#date").datepicker("setDate", new Date());jQuery("#date").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->pause(2000)
                ->press('UPDATE')
                ->assertPresent('#quote-action-container')
                ->assertSee('The Turbo Ultra Turbonator');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
            $browser->visit('/signin')
                ->type('username', $secondCompany->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $secondCompany->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/quote/' . $quote->id)
                ->assertSee('Error 401')
                ->screenshot('view-another-company-quote-test')
                ->visit('/')
                ->assertPathIs('/' . $secondCompany->domain_name . '/dashboard');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
