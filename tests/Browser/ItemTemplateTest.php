<?php
namespace Tests\Browser;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Faker\Factory as Faker;
use App\Models\Company;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class ItemTemplateTest extends DuskTestCase
{
    use DatabaseMigrations;
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
                ->type('quantity', $faker->numberBetween($min = 1, $max = 1000))
                ->type('price', $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = NULL));
            $browser
                ->script('jQuery("#description").trumbowyg("html", "' . $faker->text(200) . '");');
            $browser
                ->press('CREATE')
                ->assertPresent('#delete-itemtemplate-form');
        });
    }
}