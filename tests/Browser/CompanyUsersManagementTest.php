<?php

namespace Tests\Browser;

use App\Models\Company;
use App\Models\CompanyUserRequest;
use App\Models\User;
use Faker\Factory as Faker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class CompanyUsersManagementTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_adding_a_user_to_a_company()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/company/users')
                ->clickLink('Add User')
                ->type('username', str_random(10))
                ->type('email', $faker->unique()->safeEmail)
                ->type('full_name', $faker->name)
                ->type('phone', '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true))
                ->click('label[for="gender-female"]');
            $browser
                ->script('jQuery("#timezone").selectize()[0].selectize.setValue("UTC");');
            $browser
                ->script('jQuery("#roles").selectize()[0].selectize.setValue("user");');
            $browser
                ->press('ADD')
                ->assertPresent('#users-table')
                ->assertPathIs('/' . $company->domain_name . '/company/users');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_removing_a_user_from_a_company()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $user = factory(User::class)->create();

        $company->users()->attach($user->id);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company, $user) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/company/users');
            $browser
                ->script("jQuery(\"a[data-tooltip='Remove User'] > i\").click();");
            $browser
                ->pause(500)
                ->press('DELETE')
                ->assertPresent('#user-container')
                ->assertPathIs('/' . $company->domain_name . '/company/users');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_approve_user_request_to_join_company()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $companyUserRequest = factory(CompanyUserRequest::class)->create([
            'status' => CompanyUserRequest::STATUS_PENDING,
            'company_id' => $company->id
        ]);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company, $companyUserRequest) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/company/requests');
            $browser
                ->script("jQuery(\"form[data-tooltip='Approve User']\").submit();");
            $browser
                ->assertPresent('#request-container')
                ->assertPathIs('/' . $company->domain_name . '/company/requests');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }

    public function test_reject_user_request_to_join_company()
    {
        $company = factory(Company::class)->create();
        //Need to attach the company to the user
        $company->users()->attach($company->user_id);

        $companyUserRequest = factory(CompanyUserRequest::class)->create([
            'status' => CompanyUserRequest::STATUS_PENDING,
            'company_id' => $company->id
        ]);

        $faker = Faker::create();
        $this->browse(function (Browser $browser) use ($faker, $company, $companyUserRequest) {
            $browser->visit('/signin')
                ->type('username', $company->owner->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/' . $company->domain_name . '/dashboard')
                ->visit('/' . $company->domain_name . '/company/requests');
            $browser
                ->script("jQuery(\"form[data-tooltip='Reject User']\").submit();");
            $browser
                ->assertPresent('#request-container')
                ->assertPathIs('/' . $company->domain_name . '/company/requests');
            $browser->script('jQuery(".signmeout-btn").click()');
            $browser->assertPathIs('/signin');
        });
    }
}