<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\InvoiceRecurrence;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Tests\TestCase;

class InvoiceRecurrenceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice_recurrence()
    {
        $company = factory(Company::class)->create();

        InvoiceRecurrence::unguard();

        $invoiceRecurrence = InvoiceRecurrence::create([
            'time_interval' => '1',
            'time_period'   => 'month',
            'until_type'    => 'never',
            'until_meta'    => null,
            'rule'          => 'FREQ=MONTHLY;INTERVAL=1',
            'company_id'    => $company->id,
        ]);

        InvoiceRecurrence::reguard();

        $this->assertEquals($invoiceRecurrence->company->name, $company->name);
        $this->assertEquals('FREQ=MONTHLY;INTERVAL=1', $invoiceRecurrence->rule);
    }

    public function test_update_invoice_recurrence()
    {
        $invoiceRecurrence = factory(InvoiceRecurrence::class)->create();

        $this->assertInstanceOf(InvoiceRecurrence::class, $invoiceRecurrence);

        $invoiceRecurrence->time_period = 'week';
        $invoiceRecurrence->until_type = 'occurence';
        $invoiceRecurrence->save();
        $invoiceRecurrence->refresh();

        $this->assertEquals('week', $invoiceRecurrence->time_period);
        $this->assertEquals('occurence', $invoiceRecurrence->until_type);

        $data = [
            'time_interval' => '3',
            'time_period'   => 'year',
            'until_type'    => 'date',
            'until_meta'    => '2020-10-31 00:00:00',
        ];

        $this->expectException(MassAssignmentException::class);

        $invoiceRecurrence->fill($data);
        $invoiceRecurrence->save();
        $invoiceRecurrence->refresh();

        $this->assertNotEquals('3', $invoiceRecurrence->time_interval);
        $this->assertNotEquals('year@example.com', $invoiceRecurrence->time_period);
        $this->assertNotEquals('date', $invoiceRecurrence->until_type);
        $this->assertNotEquals('2020-10-31 00:00:00', $invoiceRecurrence->until_meta);
    }

    public function test_delete_invoice_recurrence()
    {
        $company = factory(Company::class)->create();
        $invoiceRecurrence = factory(InvoiceRecurrence::class)->create([
            'company_id' => $company->id,
        ]);

        $this->assertInstanceOf(InvoiceRecurrence::class, $invoiceRecurrence);
        $invoiceRecurrence = $invoiceRecurrence->delete();

        $this->assertEquals('true', json_encode($invoiceRecurrence));
        $this->assertEmpty($company->events);
    }
}
