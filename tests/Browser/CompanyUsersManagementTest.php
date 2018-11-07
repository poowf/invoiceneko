<?php
namespace Tests\Browser;
use App\Models\Company;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
class CompanyUsersManagementTest extends DuskTestCase
{
    use DatabaseMigrations;
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function test_adding_a_user_to_a_company()
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
                ->visit('/company/users')
                ->click("a[href='{$this->baseUrl()}/company/users/create']")
                ->type('username', str_random(10))
                ->type('email', $faker->unique()->safeEmail)
                ->type('full_name', $faker->name)
                ->type('phone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true))
                ->click('label[for="gender-female"]')
                ->press('ADD')
                ->assertPresent('#users-table')
                ->assertPathIs('/company/users');
        });
    }
}