<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateClientTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testClient()
    {
        $client = factory(App\Models\Client::class)->make(function ($client) {
            $client->company()->save(factory(App\Models\Company::class)->make());
        });

        $this->assertTrue(true);
    }



}
