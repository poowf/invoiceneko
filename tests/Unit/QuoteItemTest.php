<?php

namespace Tests\Unit;

use App\Models\Quote;
use App\Models\QuoteItem;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Tests\TestCase;

class QuoteItemTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_quote_item()
    {
        $quote = Quote::factory()->create();

        QuoteItem::unguard();

        $quoteItem = QuoteItem::create([
            'name'        => 'This is a Quote Item la',
            'quantity'    => '250',
            'price'       => '5.00',
            'description' => 'asfdasfasfasfsf<p>asasdfasdfasfas</p>',
            'quote_id'    => $quote->id,
        ]);

        QuoteItem::reguard();

        $this->assertEquals($quoteItem->quote->id, $quote->id);
        $this->assertEquals('asfdasfasfasfsf<p>asasdfasdfasfas</p>', $quoteItem->description);
    }

    public function test_update_quote_item()
    {
        $quote = Quote::factory()->create();
        $quoteItem = QuoteItem::factory()->create([
            'quote_id' => $quote->id,
        ]);
        $this->assertInstanceOf(QuoteItem::class, $quoteItem);

        $quoteItem->price = '500.00';
        $quoteItem->quantity = '2000';
        $quoteItem->save();
        $quoteItem->refresh();

        $this->assertEquals('500.00', $quoteItem->price);
        $this->assertEquals('2000', $quoteItem->quantity);

        $data = [
            'price'    => '213131.00',
            'quantity' => '25000',
        ];

        $this->expectException(MassAssignmentException::class);

        $quoteItem->fill($data);
        $quoteItem->save();
        $quoteItem->refresh();

        $this->assertNotEquals('213131.00', $quoteItem->price);
        $this->assertNotEquals('25000', $quoteItem->quantity);
    }

    public function test_delete_quote_item()
    {
        $quote = Quote::factory()->create();
        $quoteItem = QuoteItem::factory()->create([
            'quote_id' => $quote->id,
        ]);

        $this->assertInstanceOf(QuoteItem::class, $quoteItem);
        $quoteItem = $quoteItem->delete();

        $this->assertEquals('true', json_encode($quoteItem));
        $this->assertEmpty($quote->items);
    }
}
