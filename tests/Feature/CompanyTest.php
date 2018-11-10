<?php

namespace Tests\Feature;

use App\Models\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

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
            'crn' => '201810000A',
            'phone' => '+6579328669',
            'email' => 'bunny@poowf.com',
            'user_id' => $user->id
        ]);

        Company::reguard();

        $this->assertEquals($company->owner->name, $user->name);
        $this->assertEquals('poowf-labs', $company->slug);
    }


    public function test_company_has_a_company_settings_relationship()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $this->assertTrue(isset($company->settings));
        $this->assertEquals($company->id, $company->settings->company_id);
    }
}
