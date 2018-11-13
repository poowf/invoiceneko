<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\CompanyUserRequest;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyUserRequestTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_company_user_request()
    {
        $company = factory(Company::class)->create();

        CompanyUserRequest::unguard();

        $companyUserRequest = CompanyUserRequest::create([
            'full_name' => 'Poowf Bunny',
            'email' => 'bunny@poowf.com',
            'phone' => '+6579328669',
            'token' => 'asdfounasifdnasfinasasdfasf',
            'status' => '1',
            'company_id' => $company->id
        ]);

        CompanyUserRequest::reguard();

        $this->assertEquals($companyUserRequest->company->name, $company->name);
        $this->assertEquals('bunny@poowf.com', $companyUserRequest->email);
    }

    public function test_update_company_user_request()
    {
        $company = factory(Company::class)->create();
        $companyUserRequest = factory(CompanyUserRequest::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(CompanyUserRequest::class, $companyUserRequest);

        $companyUserRequest->full_name = 'tehsecretname';
        $companyUserRequest->token = 'bargabarbararba';
        $companyUserRequest->save();
        $companyUserRequest->refresh();

        $this->assertEquals('tehsecretname', $companyUserRequest->full_name);
        $this->assertEquals('bargabarbararba', $companyUserRequest->token);

        $data = [
            'full_name' => 'NyanIndustries',
            'email' => 'nowaythiscannotbe@example.com',
            'phone' => '+659774123',
            'token' => 'asdfnasuifasuifnasidfas',
        ];

        $companyUserRequest->fill($data);
        $companyUserRequest->save();
        $companyUserRequest->refresh();

        $this->assertEquals('NyanIndustries', $companyUserRequest->full_name);
        $this->assertEquals('nowaythiscannotbe@example.com', $companyUserRequest->email);
        $this->assertEquals('+659774123', $companyUserRequest->phone);
        $this->assertNotEquals('asdfnasuifasuifnasidfas', $companyUserRequest->token);
    }


    public function test_delete_company_user_request()
    {
        $company = factory(Company::class)->create();
        $companyUserRequest = factory(CompanyUserRequest::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(CompanyUserRequest::class, $companyUserRequest);
        $companyUserRequest = $companyUserRequest->delete();

        $this->assertEquals('true', json_encode($companyUserRequest));
        $this->assertEmpty($company->requests);
    }
}
