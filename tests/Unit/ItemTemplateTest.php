<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\ItemTemplate;
use Tests\TestCase;

class ItemTemplateTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_item_template()
    {
        $company = Company::factory()->create();

        ItemTemplate::unguard();

        $itemTemplate = ItemTemplate::create([
            'name' => 'This is an Item Template la',
            'quantity' => '250',
            'price' => '5.00',
            'description' => 'asfdasfasfasfsf<p>asasdfasdfasfas</p>',
            'company_id' => $company->id,
        ]);

        ItemTemplate::reguard();

        $this->assertEquals($itemTemplate->company->id, $company->id);
        $this->assertEquals('<p>asfdasfasfasfsf<p>asasdfasdfasfas</p></p>\n', $itemTemplate->description);
    }

    public function test_update_item_template()
    {
        $company = Company::factory()->create();
        $company2 = Company::factory()->create();
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);
        $this->assertInstanceOf(ItemTemplate::class, $itemTemplate);

        $itemTemplate->price = '500.00';
        $itemTemplate->quantity = '2000';
        $itemTemplate->save();
        $itemTemplate->refresh();

        $this->assertEquals('500.00', $itemTemplate->price);
        $this->assertEquals('2000', $itemTemplate->quantity);

        $data = [
            'price' => '213131.00',
            'quantity' => '25000',
            'company_id' => $company2->id,
        ];

        $itemTemplate->fill($data);
        $itemTemplate->save();
        $itemTemplate->refresh();

        $this->assertEquals('213131.00', $itemTemplate->price);
        $this->assertEquals('25000', $itemTemplate->quantity);
        $this->assertNotEquals($company2->id, $itemTemplate->company_id);
    }

    public function test_delete_item_template()
    {
        $company = Company::factory()->create();
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        $this->assertInstanceOf(ItemTemplate::class, $itemTemplate);
        $itemTemplate = $itemTemplate->delete();

        $this->assertEquals('true', json_encode($itemTemplate));
        $this->assertEmpty($company->itemtemplates);
    }
}
