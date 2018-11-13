<?php

namespace Tests\Unit;

use App\Models\InvoiceItemTemplate;
use App\Models\InvoiceTemplate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceItemTemplateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice_item_template()
    {
        $invoiceTemplate = factory(InvoiceTemplate::class)->create();

        InvoiceItemTemplate::unguard();

        $invoiceItemTemplate = InvoiceItemTemplate::create([
            'name' => 'This is an Invoice Item Template la',
            'quantity' => '250',
            'price' => '5.00',
            'description' => 'asfdasfasfasfsf<p>asasdfasdfasfas</p>',
            'invoice_template_id' => $invoiceTemplate->id
        ]);

        InvoiceItemTemplate::reguard();

        $this->assertEquals($invoiceItemTemplate->template->id, $invoiceTemplate->id);
        $this->assertEquals('asfdasfasfasfsf<p>asasdfasdfasfas</p>', $invoiceItemTemplate->description);
    }

    public function test_update_invoice_item_template()
    {
        $invoiceTemplate = factory(InvoiceTemplate::class)->create();
        $invoiceTemplate2 = factory(InvoiceTemplate::class)->create();
        $invoiceItemTemplate = factory(InvoiceItemTemplate::class)->create([
            'invoice_template_id' => $invoiceTemplate->id
        ]);
        $this->assertInstanceOf(InvoiceItemTemplate::class, $invoiceItemTemplate);

        $invoiceItemTemplate->price = "500.00";
        $invoiceItemTemplate->quantity = "2000";
        $invoiceItemTemplate->save();
        $invoiceItemTemplate->refresh();

        $this->assertEquals('500.00', $invoiceItemTemplate->price);
        $this->assertEquals('2000', $invoiceItemTemplate->quantity);

        $data = [
            'price' => '213131.00',
            'quantity' => '25000',
            'invoice_template_id' => $invoiceTemplate2->id,
        ];

        $invoiceItemTemplate->fill($data);
        $invoiceItemTemplate->save();
        $invoiceItemTemplate->refresh();

        $this->assertEquals('213131.00', $invoiceItemTemplate->price);
        $this->assertEquals('25000', $invoiceItemTemplate->quantity);
        $this->assertNotEquals($invoiceTemplate2->id, $invoiceItemTemplate->invoice_template_id);
    }

    public function test_delete_invoice_item_template()
    {
        $invoiceTemplate = factory(InvoiceTemplate::class)->create();
        $invoiceItemTemplate = factory(InvoiceItemTemplate::class)->create([
            'invoice_template_id' => $invoiceTemplate->id
        ]);

        $this->assertInstanceOf(InvoiceItemTemplate::class, $invoiceItemTemplate);
        $invoiceItemTemplate = $invoiceItemTemplate->delete();

        $this->assertEquals('true', json_encode($invoiceItemTemplate));
        $this->assertEmpty($invoiceTemplate->itemtemplates);
    }
}
