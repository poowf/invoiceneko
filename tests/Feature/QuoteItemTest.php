<?php

namespace Tests\Feature;

use App\Models\QuoteItem;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuoteItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_quote_item()
    {
        $quote = factory(\App\Models\Quote::class)->create();

        QuoteItem::unguard();

        $quoteitem = QuoteItem::create([
            'name' => 'This is a Quote Item la',
            'quantity' => '250',
            'price' => '5.00',
            'description' => 'asfdasfasfasfsf<p>asasdfasdfasfas</p>',
            'quote_id' => $quote->id
        ]);

        QuoteItem::reguard();

        $this->assertEquals($quoteitem->quote->id, $quote->id);
        $this->assertEquals('asfdasfasfasfsf<p>asasdfasdfasfas</p>', $quoteitem->description);
    }
}
