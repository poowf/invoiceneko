<?php

namespace Tests\Unit;

use App\Models\Quote;
use App\Models\Client;
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
        $client = factory(Client::class)->create();

        Quote::unguard();

        $quote = Quote::create([
            'nice_quote_id' => 'PWFQ-000001',
            'date' => '2 August, 2018',
            'netdays' => '20',
            'total' => '650.50',
            'share_token' => '7e57d004-2b97-0e7a-b45f-5387367791cd',
            'status' => '2',
            'archived' => '0',
            'client_id' => $client->id,
            'company_id' => $client->company->id
        ]);

        Quote::reguard();

        $this->assertEquals($client->company->name, $quote->client->company->name);
        $this->assertEquals('7e57d004-2b97-0e7a-b45f-5387367791cd', $quote->share_token);
        $this->assertEquals('2018-08-22 00:00:00', $quote->duedate->timezone(config('app.timezone'))->toDateTimeString());
    }

    public function test_update_quote()
    {
        $quote = factory(Quote::class)->create();
        $this->assertInstanceOf(Quote::class, $quote);

        $quote->total = '12312313.00';
        $quote->save();
        $quote->refresh();

        $this->assertEquals('12312313.00', $quote->total);

        //Testing fillable properties
        $data = [
            'date' => '1 January, 2018',
            'netdays' => '25',
            'total' => '19293313.00',
        ];

        $quote->fill($data);
        $quote->save();
        $quote->refresh();

        $this->assertEquals('25', $quote->netdays);
        $this->assertEquals('2018-01-26 00:00:00', $quote->duedate->timezone(config('app.timezone'))->toDateTimeString());
        $this->assertNotEquals('19293313.00', $quote->total);
    }

    public function test_delete_quote()
    {
        $quote = factory(Quote::class)->create();
        $this->assertInstanceOf(Quote::class, $quote);
        $quote = $quote->delete();

        $this->assertEquals('true', json_encode($quote));
    }
}
