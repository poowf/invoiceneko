<?php

namespace Tests\Browser;

use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PaymentTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function test_create_a_payment()
    {
        $invoice = Invoice::factory()->create();
        $company = $invoice->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $invoice, $company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/' . $company->domain_name . '/payments')
                ->clickLink('Create')
                ->assertPathIs('/' . $company->domain_name . '/payment/create')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser->script(
                'jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' .
                    Carbon::now()->format('j F, Y') .
                    '");',
            );
            $browser->script('jQuery("#invoice_id").selectize()[0].selectize.setValue("' . $invoice->id . '");');
            $browser->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser->press('SUBMIT')->assertPresent('#payment-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_update_a_payment()
    {
        $invoice = Invoice::factory()->create();
        $company = $invoice->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $payment = Payment::factory()->create([
            'invoice_id' => $invoice->id,
            'company_id' => $invoice->company_id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $company, $payment) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/' . $company->domain_name . '/payments');
            $browser->script("jQuery(\"a[href='{$this->baseUrl()}/{$company->domain_name}/payment/{$payment->id}/edit'] > i\").click();");
            $browser
                ->assertPathBeginsWith('/' . $company->domain_name . '/payment')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser->script(
                'jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' .
                    Carbon::now()->format('j F, Y') .
                    '");',
            );
            $browser->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#payment-container')
                ->assertPathIs('/' . $company->domain_name . '/payments');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_delete_a_payment()
    {
        $invoice = Invoice::factory()->create();
        $company = $invoice->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $payment = Payment::factory()->create([
            'invoice_id' => $invoice->id,
            'company_id' => $invoice->company_id,
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/' . $company->domain_name . '/payments');
            $browser->script('jQuery(".payment-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#payment-container')
                ->assertPathIs('/' . $company->domain_name . '/payments');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_end_to_end_payment()
    {
        $invoice = Invoice::factory()->create();
        $company = $invoice->company;
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $invoice, $company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/' . $company->domain_name . '/payments')
                ->clickLink('Create')
                ->assertPathIs('/' . $company->domain_name . '/payment/create')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser->script(
                'jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' .
                    Carbon::now()->format('j F, Y') .
                    '");',
            );
            $browser->script('jQuery("#invoice_id").selectize()[0].selectize.setValue("' . $invoice->id . '");');
            $browser->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#payment-container')
                ->clickLink('Payments')
                ->assertPathIs('/' . $company->domain_name . '/payments');
            $browser->script("jQuery(\"a[data-tooltip='Edit Payment'] > i\").click();");
            $browser
                ->assertPathBeginsWith('/' . $company->domain_name . '/payment')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser->script(
                'jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' .
                    Carbon::now()->format('j F, Y') .
                    '");',
            );
            $browser->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#payment-container')
                ->assertPathIs('/' . $company->domain_name . '/payments');
            $browser->script('jQuery(".payment-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#payment-container')
                ->assertPathIs('/' . $company->domain_name . '/payments');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
