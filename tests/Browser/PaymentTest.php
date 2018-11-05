<?php

namespace Tests\Browser;

use App\Models\Invoice;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class PaymentTest extends DuskTestCase
{
    use DatabaseMigrations;

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

        Log::info($invoice);

        $faker = Faker::create();

        $this->browse(function (Browser $browser) use ($faker, $invoice) {
            $browser->visit('/signin')
                ->type('username', $invoice->company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/payments')
                ->click("a[href='{$this->baseUrl()}/payment/create']")
                ->assertPathIs('/payment/create')
                ->type('amount', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = NULL))
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
        });
    }
}
