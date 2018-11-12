<?php

namespace Tests\Unit;

use App\Models\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_create_client()
    {
        $company = factory(\App\Models\Company::class)->create();

        Client::unguard();

        $client = Client::create([
            'companyname' => 'Poowf Labs',
            'phone' => '+6581234567',
            'block' => '123',
            'street' => '123 Street Name',
            'unitnumber' => '00-00',
            'postalcode' => '123456',
            'country_code' => 'SG',
            'nickname' => 'Poowf the Bunny',
            'crn' => '201810000A',
            'website' => 'http://poowf.com',
            'contactsalutation' => 'Ms.',
            'contactfirstname' => 'Poowf',
            'contactlastname' => 'Bunny',
            'contactgender' => 'female',
            'contactemail' => 'bunny@poowf.com',
            'contactphone' => '+6579328669',
            'company_id' => $company->id
        ]);

        Client::unguard();

        $this->assertEquals($client->company->name, $company->name);
        $this->assertEquals('Poowf Labs', $client->companyname);
    }

}
