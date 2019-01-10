<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Invoice;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice()
    {
        $client = factory(Client::class)->create();

        Invoice::unguard();

        $invoice = Invoice::create([
            'nice_invoice_id' => 'PWF-000001',
            'date'            => '2 September, 2018',
            'netdays'         => '20',
            'total'           => '650.80',
            'share_token'     => '7e57d004-2b97-0e7a-b45f-5387367791cd',
            'status'          => '2',
            'archived'        => '0',
            'notify'          => '0',
            'client_id'       => $client->id,
            'company_id'      => $client->company->id,
        ]);

        Invoice::reguard();

        $this->assertEquals($client->company->name, $invoice->getClient()->company->name);
        $this->assertEquals('7e57d004-2b97-0e7a-b45f-5387367791cd', $invoice->share_token);
        $this->assertEquals('2018-09-22 00:00:00', $invoice->duedate->timezone(config('app.timezone'))->toDateTimeString());
    }

    public function test_update_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $this->assertInstanceOf(Invoice::class, $invoice);

        $invoice->total = '12312313.00';
        $invoice->save();
        $invoice->refresh();

        $this->assertEquals('12312313.00', $invoice->total);

        //Testing fillable properties
        $data = [
            'date'    => '1 November, 2018',
            'netdays' => '25',
            'total'   => '19293313.00',
        ];

        $invoice->fill($data);
        $invoice->save();
        $invoice->refresh();

        $this->assertEquals('25', $invoice->netdays);
        $this->assertEquals('2018-11-26 00:00:00', $invoice->duedate->timezone(config('app.timezone'))->toDateTimeString());
        $this->assertNotEquals('19293313.00', $invoice->total);
    }

    public function test_delete_invoice()
    {
        $invoice = factory(Invoice::class)->create();
        $this->assertInstanceOf(Invoice::class, $invoice);
        $invoice = $invoice->delete();

        $this->assertEquals('true', json_encode($invoice));
    }
}
