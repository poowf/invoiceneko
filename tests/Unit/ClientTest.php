<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Company;
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
        $company = factory(Company::class)->create();

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

        Client::reguard();

        $this->assertEquals($client->company->name, $company->name);
        $this->assertEquals('Poowf Labs', $client->companyname);
    }

    public function test_update_client()
    {
        $company = factory(Company::class)->create();
        $company2 = factory(Company::class)->create();
        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(Client::class, $client);

        $client->companyname = 'Meow Meow Incorporated';
        $client->phone = '+6564142762';
        $client->save();
        $client->refresh();

        $this->assertEquals('Meow Meow Incorporated', $client->companyname);
        $this->assertEquals('+6564142762', $client->phone);

        $data = [
            'postalcode' => '585151',
            'country_code' => 'SG',
            'nickname' => 'Shoop da Whoop',
            'crn' => 'C-ARE-AND',
            'website' => 'itsawebsite.com',
            'company_id' => $company2->id
        ];

        $client->fill($data);
        $client->save();
        $client->refresh();

        $this->assertEquals('585151', $client->postalcode);
        $this->assertEquals('SG', $client->country_code);
        $this->assertEquals('Shoop da Whoop', $client->nickname);
        $this->assertEquals('C-ARE-AND', $client->crn);
        $this->assertEquals('itsawebsite.com', $client->website);
        $this->assertNotEquals($company2->id, $client->company_id);
    }

    public function test_delete_client()
    {
        $company = factory(Company::class)->create();
        $client = factory(Client::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(Client::class, $client);

        $client = $client->delete();

        $this->assertEquals('true', json_encode($client));
        $this->assertEmpty($company->clients);
    }
}
