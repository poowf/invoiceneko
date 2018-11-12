<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_create_user()
    {
        $company = factory(\App\Models\Company::class)->create();

        User::unguard();

        $user = User::create([
            'full_name' => 'Poowf Bunny',
            'username' => 'poowf',
            'password' => 'secret',
            'email' => 'bunny@poowf.com',
            'phone' => '+6579328669',
            'gender' => 'female',
            'remember_token' => 'sadfaxsfie',
            'company_id' => $company->id
        ]);

        User::reguard();

        $this->assertEquals($user->company->name, $company->name);
        $this->assertEquals('bunny@poowf.com', $user->email);
    }

}
