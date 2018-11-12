<?php

namespace Tests\Unit;

use App\Models\Company;
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
        $company = factory(Company::class)->create();

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

    public function test_update_company_address()
    {
        $company = factory(Company::class)->create();
        $companyAddress = factory(CompanyAddress::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(CompanyAddress::class, $companyAddress);

        $companyAddress->buildingtype = '2';
        $companyAddress->postalcode = '234523';
        $companyAddress->save();
        $companyAddress->refresh();

        $this->assertEquals('2', $companyAddress->buildingtype);
        $this->assertEquals('234523', $companyAddress->postalcode);

        $data = [
            'block' => '1231',
            'street' => 'Monster Moon Street',
            'unitnumber' => '#100-100',
        ];

        $companyAddress->fill($data);
        $companyAddress->save();
        $companyAddress->refresh();

        $this->assertEquals('1231', $companyAddress->block);
        $this->assertEquals('Monster Moon Street', $companyAddress->street);
        $this->assertEquals('#100-100', $companyAddress->unitnumber);
    }


    public function test_delete_company_address()
    {
        $company = factory(Company::class)->create();
        $companyAddress = factory(CompanyAddress::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertInstanceOf(CompanyAddress::class, $companyAddress);

        $companyAddress = $companyAddress->delete();

        $this->assertEquals('true', json_encode($companyAddress));
        $this->assertEmpty($company->address);
    }
}
