<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\InvoiceEvent;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceEventTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice_event()
    {
        $company = factory(Company::class)->create();

        InvoiceEvent::unguard();

        $invoiceEvent = InvoiceEvent::create([
            'time_interval' => '1',
            'time_period' => 'month',
            'until_type' => 'never',
            'until_meta' => null,
            'rule' => 'FREQ=MONTHLY;INTERVAL=1',
            'company_id' => $company->id
        ]);

        InvoiceEvent::reguard();

        $this->assertEquals($invoiceEvent->company->name, $company->name);
        $this->assertEquals('FREQ=MONTHLY;INTERVAL=1', $invoiceEvent->rule);
    }

    public function test_update_invoice_event()
    {
        $invoiceEvent = factory(InvoiceEvent::class)->create();

        $this->assertInstanceOf(InvoiceEvent::class, $invoiceEvent);

        $invoiceEvent->time_period = 'week';
        $invoiceEvent->until_type = 'occurence';
        $invoiceEvent->save();
        $invoiceEvent->refresh();

        $this->assertEquals('week', $invoiceEvent->time_period);
        $this->assertEquals('occurence', $invoiceEvent->until_type);

        $data = [
            'time_interval' => '3',
            'time_period' => 'year',
            'until_type' => 'date',
            'until_meta' => '2020-10-31 00:00:00',
        ];

        $this->expectException(MassAssignmentException::class);

        $invoiceEvent->fill($data);
        $invoiceEvent->save();
        $invoiceEvent->refresh();

        $this->assertNotEquals('3', $invoiceEvent->time_interval);
        $this->assertNotEquals('year@example.com', $invoiceEvent->time_period);
        $this->assertNotEquals('date', $invoiceEvent->until_type);
        $this->assertNotEquals('2020-10-31 00:00:00', $invoiceEvent->until_meta);
    }


    public function test_delete_invoice_event()
    {
        $company = factory(Company::class)->create();
        $invoiceEvent = factory(InvoiceEvent::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(InvoiceEvent::class, $invoiceEvent);
        $invoiceEvent = $invoiceEvent->delete();

        $this->assertEquals('true', json_encode($invoiceEvent));
        $this->assertEmpty($company->events);
    }
}
