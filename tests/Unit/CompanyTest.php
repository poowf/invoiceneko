<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\CompanyAddress;
use App\Models\CompanySetting;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_company()
    {
        $user = factory(\App\Models\User::class)->create();

        Company::unguard();

        $company = Company::create([
            'name' => 'Poowf Labs',
            'invoice_index' => '2342',
            'quote_index' => '12313',
            'timezone' => 'UTC',
            'crn' => '201810000A',
            'phone' => '+6579328669',
            'email' => 'bunny@poowf.com',
            'user_id' => $user->id
        ]);

        Company::reguard();

        $this->assertEquals($company->owner->name, $user->name);
        $this->assertEquals('poowf-labs', $company->slug);
    }

    public function test_update_company()
    {
        $user = factory(\App\Models\User::class)->create();
        $company = factory(Company::class)->create();


        $this->assertInstanceOf(Company::class, $company);

        $company->name = 'Nyan Industries';
        $company->quote_index = '234523';
        $company->phone = '+6564142762';
        $company->save();
        $company->refresh();

        $this->assertEquals('nyan-industries', $company->slug);
        $this->assertEquals('234523', $company->quote_index);
        $this->assertEquals('+6564142762', $company->phone);

        $data = [
            'name' => 'Nyan Industries',
            'crn' => 'CERRRANDFSADFS',
            'domain_name' => 'nekonyanyananannynayynaynaya.com',
            'phone' => '+659774123',
            'invoice_index' => '2342',
            'user_id' => $user->id
        ];

        $company->fill($data);
        $company->save();
        $company->refresh();

        $this->assertEquals('Nyan Industries', $company->name);
        $this->assertEquals('CERRRANDFSADFS', $company->crn);
        $this->assertEquals('nekonyanyananannynayynaynaya.com', $company->domain_name);
        $this->assertEquals('+659774123', $company->phone);
        $this->assertNotEquals('2342', $company->invoice_index);
    }

    public function test_delete_company()
    {
        $company = factory(Company::class)->create();


        $this->assertInstanceOf(Company::class, $company);

        $company = $company->delete();

        $this->assertEquals('true', json_encode($company));
    }

    public function test_company_has_a_company_settings_relationship()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user

        $this->assertTrue(isset($company->settings));
        $this->assertInstanceOf(CompanySetting::class, $company->settings);
        $this->assertEquals($company->id, $company->settings->company_id);
    }
}
