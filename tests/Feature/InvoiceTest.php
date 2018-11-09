<?php

namespace Tests\Feature;

use App\Models\Invoice;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice()
    {
        $client = factory(\App\Models\Client::class)->create();

        Invoice::unguard();

        $invoice = Invoice::create([
            'nice_invoice_id' => 'PWF-000001',
            'date' => '2018-01-01 12:01:00',
            'duedate' => '2018-01-01 12:01:00',
            'netdays' => '20',
            'total' => '650.80',
            'share_token' => '7e57d004-2b97-0e7a-b45f-5387367791cd',
            'status' => '2',
            'archived' => '0',
            'notify' => '0',
            'client_id' => $client->id,
            'company_id' => $client->company->id
        ]);

        Invoice::reguard();

        $this->assertEquals($invoice->client->company->name, $client->company->name);
        $this->assertEquals('7e57d004-2b97-0e7a-b45f-5387367791cd', $invoice->share_token);
    }

}
