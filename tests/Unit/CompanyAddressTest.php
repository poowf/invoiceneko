<?php

namespace Tests\Unit;

use App\Models\CompanyAddress;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_company_address()
    {
        $company = factory(\App\Models\Company::class)->create();

        CompanyAddress::unguard();

        $companyAddress = CompanyAddress::create([
            'block' => '123',
            'street' => '123 Street Name',
            'unitnumber' => '00-00',
            'postalcode' => '123456',
            'buildingtype' => '1',
            'company_id' => $company->id
        ]);

        CompanyAddress::reguard();

        $this->assertEquals($companyAddress->company->name, $company->name);
        $this->assertEquals('123 Street Name', $companyAddress->street);
    }
}
