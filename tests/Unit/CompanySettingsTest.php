<?php

namespace Tests\Unit;

use App\Models\CompanySettings;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanySettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_company_settings()
    {
        $company = factory(\App\Models\Company::class)->create();

        CompanySettings::unguard();

        $companySettings = CompanySettings::create([
            'invoice_prefix' => 'PWF',
            'quote_prefix' => 'PWFQ',
            'invoice_conditions' => 'asdfasfdasfasfasdf <strong>asdfasdfasdf</strong> asfdassafas <p>asdfasdfasdfas</p>',
            'quote_conditions' => 'asdfasfdasfasfasdf <strong>asdfasdfasdf</strong> asfdassafas <p>asdfasdfasdfas</p>',
            'tax' => '20',
            'company_id' => $company->id
        ]);

        CompanySettings::reguard();

        $this->assertEquals($companySettings->company->name, $company->name);
        $this->assertEquals('asdfasfdasfasfasdf <strong>asdfasdfasdf</strong> asfdassafas <p>asdfasdfasdfas</p>', $companySettings->invoice_conditions);
    }
}
