<?php

namespace Tests\Feature;

use App\Models\ItemTemplate;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemTemplateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_item_template()
    {
        $company = factory(\App\Models\Company::class)->create();

        ItemTemplate::unguard();

        $itemtemplate = ItemTemplate::create([
            'name' => 'This is an Item Template la',
            'quantity' => '250',
            'price' => '5.00',
            'description' => 'asfdasfasfasfsf<p>asasdfasdfasfas</p>',
            'company_id' => $company->id
        ]);

        ItemTemplate::reguard();

        $this->assertEquals($itemtemplate->company->id, $company->id);
        $this->assertEquals('asfdasfasfasfsf<p>asasdfasdfasfas</p>', $itemtemplate->description);
    }
}
