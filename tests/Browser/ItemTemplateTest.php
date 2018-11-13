<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Factory as Faker;
use App\Models\Company;
use App\Models\ItemTemplate;

class ItemTemplateTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_create_an_item_template()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();
        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/itemtemplates')
                ->click("a[href='{$this->baseUrl()}/itemtemplate/create']")
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('CREATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/itemtemplate');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_update_an_item_template()
    {
        $company = factory(Company::class)->create();
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id
        ]);

        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/itemtemplates');
            $browser
                ->script("jQuery(\"a[href='{$this->baseUrl()}/itemtemplate/{$itemTemplate->id}/edit'] > i\").click();");
            $browser
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('UPDATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/itemtemplate');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_delete_an_item_template()
    {
        $company = factory(Company::class)->create();
        $itemTemplate = factory(ItemTemplate::class)->create([
            'company_id' => $company->id
        ]);

        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/itemtemplates');
            $browser
                ->script('jQuery(".itemtemplate-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#itemtemplate-container')
                ->assertPathIs('/itemtemplates');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_end_to_end_item_template()
    {
        $company = factory(Company::class)->create();
        //Need to assign the company_id to the user
        $company->owner->company_id = $company->id;
        $company->owner->save();
        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/dashboard')
                ->visit('/itemtemplates')
                ->click("a[href='{$this->baseUrl()}/itemtemplate/create']")
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('CREATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/itemtemplate')
                ->clickLink('Edit')
                ->assertSee('Update Item Template')
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser
                ->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('UPDATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/itemtemplate')
                ->clickLink('Delete')
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#itemtemplate-container')
                ->assertPathIs('/itemtemplates');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }
}