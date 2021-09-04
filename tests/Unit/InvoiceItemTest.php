<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Tests\TestCase;

class InvoiceItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_invoice_item()
    {
        $invoice = Invoice::factory()->create();

        InvoiceItem::unguard();

        $invoiceItem = InvoiceItem::create([
            'name' => 'This is an Invoice Item la',
            'quantity' => '250',
            'price' => '5.00',
            'description' => 'asfdasfasfasfsf<p>asasdfasdfasfas</p>',
            'invoice_id' => $invoice->id,
        ]);

        InvoiceItem::reguard();

        $this->assertEquals($invoiceItem->invoice->id, $invoice->id);
        $this->assertEquals("<p>asfdasfasfasfsf<p>asasdfasdfasfas</p></p>\n", $invoiceItem->description);
    }

    public function test_update_invoice_item()
    {
        $invoice = Invoice::factory()->create();
        $invoiceItem = InvoiceItem::factory()->create([
            'invoice_id' => $invoice->id,
        ]);
        $this->assertInstanceOf(InvoiceItem::class, $invoiceItem);

        $invoiceItem->price = '500.00';
        $invoiceItem->quantity = '2000';
        $invoiceItem->save();
        $invoiceItem->refresh();

        $this->assertEquals('500.00', $invoiceItem->price);
        $this->assertEquals('2000', $invoiceItem->quantity);

        $data = [
            'price' => '213131.00',
            'quantity' => '25000',
        ];

        $this->expectException(MassAssignmentException::class);

        $invoiceItem->fill($data);
        $invoiceItem->save();
        $invoiceItem->refresh();

        $this->assertNotEquals('213131.00', $invoiceItem->price);
        $this->assertNotEquals('25000', $invoiceItem->quantity);
    }

    public function test_delete_invoice_item()
    {
        $invoice = Invoice::factory()->create();
        $invoiceItem = InvoiceItem::factory()->create([
            'invoice_id' => $invoice->id,
        ]);

        $this->assertInstanceOf(InvoiceItem::class, $invoiceItem);
        $invoiceItem = $invoiceItem->delete();

        $this->assertEquals('true', json_encode($invoiceItem));
        $this->assertEmpty($invoice->items);
    }
}
