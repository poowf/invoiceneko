<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\CompanySetting;
use Tests\TestCase;

class CompanySettingsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_company_settings()
    {
        $company = Company::factory()->create();

        CompanySetting::unguard();

        $companySettings = CompanySetting::create([
            'invoice_prefix' => 'PWF',
            'quote_prefix' => 'PWFQ',
            'invoice_conditions' => 'asdfasfdasfasfasdf <strong>asdfasdfasdf</strong> asfdassafas <p>asdfasdfasdfas</p>',
            'quote_conditions' => 'asdfasfdasfasfasdf <strong>asdfasdfasdf</strong> asfdassafas <p>asdfasdfasdfas</p>',
            'tax' => '20',
            'company_id' => $company->id,
        ]);

        CompanySetting::reguard();

        $this->assertEquals($companySettings->company->name, $company->name);
        $this->assertEquals(
            '<p>asdfasfdasfasfasdf <strong>asdfasdfasdf</strong> asfdassafas <p>asdfasdfasdfas</p></p>\n',
            $companySettings->invoice_conditions,
        );
    }

    public function test_update_company_settings()
    {
        $company = Company::factory()->create();
        $companySettings = CompanySetting::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertInstanceOf(CompanySetting::class, $companySettings);

        $companySettings->quote_conditions =
            'THIS IS SHTEASDF SACONDITIONS OF THE QUOTE OMGOMGOMGOGMG THESE ARE NOT THE DROIDS YOU ARE LOOKING FOR';
        $companySettings->tax = '12';
        $companySettings->save();
        $companySettings->refresh();

        $this->assertEquals(
            '<p>THIS IS SHTEASDF SACONDITIONS OF THE QUOTE OMGOMGOMGOGMG THESE ARE NOT THE DROIDS YOU ARE LOOKING FOR</p>\n',
            $companySettings->quote_conditions,
        );
        $this->assertEquals('12', $companySettings->tax);

        $data = [
            'quote_prefix' => 'OINKQ',
            'invoice_conditions' => 'I TAWT I SAW A PUTTY TAT',
            'quote_conditions' => 'THIS IS TEH CONDIXION.',
            'tax' => '5',
        ];

        $companySettings->fill($data);
        $companySettings->save();
        $companySettings->refresh();

        $this->assertEquals('OINKQ', $companySettings->quote_prefix);
        $this->assertEquals('I TAWT I SAW A PUTTY TAT', $companySettings->invoice_conditions);
        $this->assertEquals('THIS IS TEH CONDIXION.', $companySettings->quote_conditions);
        $this->assertEquals('5', $companySettings->tax);
    }

    public function test_delete_company_settings()
    {
        $company = Company::factory()->create();
        $companySettings = $company->settings;

        $this->assertInstanceOf(CompanySetting::class, $companySettings);

        $companySettings = $companySettings->delete();

        $company->refresh();

        $this->assertEquals('true', json_encode($companySettings));
        $this->assertEmpty($company->settings);
    }
}
