<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_payment()
    {
        $company = factory(Company::class)->create();
        $invoice = factory(Invoice::class)->create([
            'company_id' => $company->id
        ]);
        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);
        Payment::unguard();

        $payment = Payment::create([
            'amount' => '516556.52',
            'receiveddate' => '2018-11-01 00:00:00',
            'invoice_id' => $invoice->id,
            'client_id' => $client->id,
            'company_id' => $company->id
        ]);

        Payment::reguard();

        $this->assertEquals($payment->invoice->id, $invoice->id);
        $this->assertEquals('516556.52', $payment->amount);
    }

    public function test_update_payment()
    {
        $company = factory(Company::class)->create();
        $invoice = factory(Invoice::class)->create([
            'company_id' => $company->id
        ]);
        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);
        $payment = factory(Payment::class)->create([
            'invoice_id' => $invoice->id,
            'client_id' => $client->id,
            'company_id' => $company->id
        ]);
        $this->assertInstanceOf(Payment::class, $payment);

        $payment->amount = "1891818.41";
        $payment->notes = "asdfasfasfasdfasfsfaffsa";
        $payment->save();
        $payment->refresh();

        $this->assertEquals('1891818.41', $payment->amount);
        $this->assertEquals('asdfasfasfasdfasfsfaffsa', $payment->notes);

        $data = [
            'amount' => '12341451541.00',
            'notes' => 'sdfasfasdvcasd asodcnaio9sjecoamfoe[casmo;cnasi;cndik; andio;asno;asdcnio; asdnio;asdcno;asdncio;asdn;',
            'receiveddate' => '2018-12-01 00:00:00'
        ];

        $payment->fill($data);
        $payment->save();
        $payment->refresh();

        $this->assertEquals('12341451541.00', $payment->amount);
        $this->assertEquals('sdfasfasdvcasd asodcnaio9sjecoamfoe[casmo;cnasi;cndik; andio;asno;asdcnio; asdnio;asdcno;asdncio;asdn;', $payment->notes);
        $this->assertNotEquals('2018-12-01 00:00:00', $payment->receiveddate->format('Y-m-d H:i:s'));
    }

    public function test_delete_payment()
    {
        $company = factory(Company::class)->create();
        $invoice = factory(Invoice::class)->create([
            'company_id' => $company->id
        ]);
        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);
        $payment = factory(Payment::class)->create([
            'invoice_id' => $invoice->id,
            'client_id' => $client->id,
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(Payment::class, $payment);
        $payment = $payment->delete();

        $this->assertEquals('true', json_encode($payment));
        $this->assertEmpty($invoice->payments);
    }
}
