<?php

namespace Tests\Browser;

use App\Models\Invoice;
use App\Models\Payment;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Carbon\Carbon;

class PaymentTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_create_a_payment()
    {
        $invoice = factory(Invoice::class)->create();
        //Need to assign the company_id to the user
        $invoice->company->owner->company_id = $invoice->company_id;
        $invoice->company->owner->save();

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $invoice) {
            $browser->visit('/signin')
                ->type('username', $invoice->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/payments')
                ->clickLink('Create')
                ->assertPathIs('/payment/create')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser
                ->script('jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#invoice_id").selectize()[0].selectize.setValue("' . $invoice->id . '");');
            $browser
                ->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#payment-container');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_update_a_payment()
    {
        $invoice = factory(Invoice::class)->create();
        //Need to assign the company_id to the user
        $invoice->company->owner->company_id = $invoice->company_id;
        $invoice->company->owner->save();
        $payment = factory(Payment::class)->create([
            'invoice_id' => $invoice->id,
            'company_id' => $invoice->company_id
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $invoice, $payment) {
            $browser->visit('/signin')
                ->type('username', $invoice->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/payments');
            $browser
                ->script("jQuery(\"a[href='{$this->baseUrl()}/payment/{$payment->id}/edit'] > i\").click();");
            $browser
                ->assertPathBeginsWith('/payment')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser
                ->script('jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#payment-container')
                ->assertPathIs('/payments');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_delete_a_payment()
    {
        $invoice = factory(Invoice::class)->create();
        //Need to assign the company_id to the user
        $invoice->company->owner->company_id = $invoice->company_id;
        $invoice->company->owner->save();
        $payment = factory(Payment::class)->create([
            'invoice_id' => $invoice->id,
            'company_id' => $invoice->company_id
        ]);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $invoice, $payment) {
            $browser->visit('/signin')
                ->type('username', $invoice->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/payments');
            $browser
                ->script('jQuery(".payment-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#payment-container')
                ->assertPathIs('/payments');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_end_to_end_payment()
    {
        $invoice = factory(Invoice::class)->create();
        //Need to assign the company_id to the user
        $invoice->company->owner->company_id = $invoice->company_id;
        $invoice->company->owner->save();

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $invoice) {
            $browser->visit('/signin')
                ->type('username', $invoice->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->clickLink('Payments')
                ->assertPathIs('/payments')
                ->clickLink('Create')
                ->assertPathIs('/payment/create')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser
                ->script('jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#invoice_id").selectize()[0].selectize.setValue("' . $invoice->id . '");');
            $browser
                ->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#payment-container')
                ->clickLink('Payments')
                ->assertPathIs('/payments');
            $browser
                ->script("jQuery(\"a[data-tooltip='Edit Payment'] > i\").click();");
            $browser
                ->assertPathBeginsWith('/payment')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999))
                ->type('notes', $faker->text(50));
            $browser
                ->script('jQuery("#receiveddate").datepicker("setDate", new Date());jQuery("#receiveddate").val("' . Carbon::now()->format('j F, Y') . '");');
            $browser
                ->script('jQuery("#mode").selectize()[0].selectize.setValue("Cheque");');
            $browser
                ->press('SUBMIT')
                ->assertPresent('#payment-container')
                ->assertPathIs('/payments');
            $browser
                ->script('jQuery(".payment-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#payment-container')
                ->assertPathIs('/payments');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }
}
