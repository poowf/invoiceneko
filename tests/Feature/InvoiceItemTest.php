<?php

namespace Tests\Feature;

use App\Models\InvoiceItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvoiceItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice_item()
    {
        $invoice = factory(\App\Models\Invoice::class)->create();

        InvoiceItem::unguard();

        $invoiceItem = InvoiceItem::create([
            'name' => 'This is an Invoice Item la',
            'quantity' => '250',
            'price' => '5.00',
            'description' => 'asfdasfasfasfsf<p>asasdfasdfasfas</p>',
            'invoice_id' => $invoice->id
        ]);

        InvoiceItem::reguard();

        $this->assertEquals($invoiceItem->invoice->id, $invoice->id);
        $this->assertEquals('asfdasfasfasfsf<p>asasdfasdfasfas</p>', $invoiceItem->description);
    }
}
