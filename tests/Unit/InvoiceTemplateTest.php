<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Company;
use App\Models\InvoiceRecurrence;
use App\Models\InvoiceTemplate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceTemplateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice_template()
    {
        $company = factory(Company::class)->create();
        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);
        $invoiceEvent = factory(InvoiceRecurrence::class)->create([
            'company_id' => $company->id
        ]);

        InvoiceTemplate::unguard();

        $invoiceTemplate = InvoiceTemplate::create([
            'date' => '2018-11-01 00:00:00',
            'netdays' => '20',
            'notify' => '0',
            'client_id' => $client->id,
            'invoice_event_id' => $invoiceEvent->id
        ]);

        InvoiceTemplate::reguard();

        $this->assertEquals($client->company->name, $invoiceTemplate->client->company->name);
        $this->assertEquals('20', $invoiceTemplate->netdays);
        $this->assertEquals('2018-11-01 00:00:00', $invoiceTemplate->date->timezone(config('app.timezone'))->toDateTimeString());
    }

    public function test_update_invoice_template()
    {
        $invoiceTemplate = factory(InvoiceTemplate::class)->create();
        $this->assertInstanceOf(InvoiceTemplate::class, $invoiceTemplate);

        $invoiceTemplate->netdays = '50';
        $invoiceTemplate->notify = '0';
        $invoiceTemplate->save();
        $invoiceTemplate->refresh();

        $this->assertEquals('50', $invoiceTemplate->netdays);
        $this->assertEquals('0', $invoiceTemplate->notify);

        //Testing fillable properties
        $data = [
            'date' => '2018-11-26 00:00:00',
            'netdays' => '30',
            'notify' => '1'
        ];

        $invoiceTemplate->fill($data);
        $invoiceTemplate->save();
        $invoiceTemplate->refresh();

        $this->assertEquals('30', $invoiceTemplate->netdays);
        $this->assertEquals('1', $invoiceTemplate->notify);
        $this->assertEquals('2018-11-26 00:00:00', $invoiceTemplate->date->timezone(config('app.timezone'))->toDateTimeString());
    }

    public function test_delete_invoice_template()
    {
        $invoiceTemplate = factory(InvoiceTemplate::class)->create();
        $this->assertInstanceOf(InvoiceTemplate::class, $invoiceTemplate);
        $invoiceTemplate = $invoiceTemplate->delete();

        $this->assertEquals('true', json_encode($invoiceTemplate));
    }
}
