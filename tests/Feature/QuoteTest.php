<?php

namespace Tests\Feature;

use App\Models\Quote;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class QuoteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_quote()
    {
        $client = factory(\App\Models\Client::class)->create();

        Quote::unguard();

        $quote = Quote::create([
            'nice_quote_id' => 'PWFQ-000001',
            'date' => '2018-01-01 12:01:00',
            'duedate' => '2018-01-01 12:01:00',
            'netdays' => '20',
            'total' => '650.50',
            'share_token' => '7e57d004-2b97-0e7a-b45f-5387367791cd',
            'status' => '2',
            'archived' => '0',
            'client_id' => $client->id,
            'company_id' => $client->company->id
        ]);

        Quote::reguard();

        $this->assertEquals($quote->client->name, $client->name);
        $this->assertEquals($quote->client->company->name, $client->company->name);
        $this->assertEquals('7e57d004-2b97-0e7a-b45f-5387367791cd', $quote->share_token);
    }
}
