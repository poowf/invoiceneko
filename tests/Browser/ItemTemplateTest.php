<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\ItemTemplate;
use Faker\Factory as Faker;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ItemTemplateTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function test_create_an_item_template()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/itemtemplates')
                ->clickLink('Create')
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('CREATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/' . $company->domain_name . '/itemtemplate');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_update_an_item_template()
    {
        $company = Company::factory()->create();
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company, $itemTemplate) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/itemtemplates');
            $browser->script(
                "jQuery(\"a[href='{$this->baseUrl()}/{$company->domain_name}/itemtemplate/{$itemTemplate->id}/edit'] > i\").click();",
            );
            $browser
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('UPDATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/' . $company->domain_name . '/itemtemplate');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_delete_an_item_template()
    {
        $company = Company::factory()->create();
        $itemTemplate = ItemTemplate::factory()->create([
            'company_id' => $company->id,
        ]);

        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/itemtemplates');
            $browser->script('jQuery(".itemtemplate-delete-btn > i").click();');
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#itemtemplate-container')
                ->assertPathIs('/' . $company->domain_name . '/itemtemplates');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }

    public function test_end_to_end_item_template()
    {
        $company = Company::factory()->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $browser
                ->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/itemtemplates')
                ->clickLink('Create')
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('CREATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/' . $company->domain_name . '/itemtemplate')
                ->clickLink('Edit')
                ->assertSee('Update Item Template')
                ->type('name', $faker->bs())
                ->type('quantity', $faker->numberBetween($min = 1, $max = 999999999))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999));
            $browser->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('UPDATE')
                ->assertSee('Item Template Details')
                ->assertPresent('#delete-itemtemplate-form')
                ->assertPathBeginsWith('/' . $company->domain_name . '/itemtemplate')
                ->clickLink('Delete')
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#itemtemplate-container')
                ->assertPathIs('/' . $company->domain_name . '/itemtemplates');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/');
        });
    }
}
