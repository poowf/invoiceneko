<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_create_user()
    {
        $company = factory(Company::class)->create();

        User::unguard();

        $user = User::create([
            'full_name'      => 'Poowf Bunny',
            'username'       => 'poowf',
            'password'       => 'secret',
            'email'          => 'bunny@poowf.com',
            'phone'          => '+6579328669',
            'gender'         => 'female',
            'remember_token' => 'sadfaxsfie',
        ]);

        User::reguard();

        $company->users()->attach($user->id);

        $this->assertTrue($company->hasUser($user));
        $this->assertEquals('bunny@poowf.com', $user->email);
    }

    public function test_update_user()
    {
        $user = factory(User::class)->create();
        $company = factory(Company::class)->create();

        $this->assertInstanceOf(User::class, $user);

        $user->twofa_secret = 'tehsecrettoken';
        $user->remember_token = 'bargabarbararba';
        $user->save();
        $user->refresh();

        $this->assertEquals('tehsecrettoken', $user->twofa_secret);
        $this->assertEquals('bargabarbararba', $user->remember_token);

        $data = [
            'username'     => 'NyanIndustries',
            'email'        => 'nowaythiscannotbe@example.com',
            'phone'        => '+659774123',
            'gender'       => 'female',
            'country_code' => 'SG',
            'company_id'   => $company->id,
        ];

        $user->fill($data);
        $user->save();
        $user->refresh();

        $this->assertEquals('nyanindustries', $user->username);
        $this->assertEquals('nowaythiscannotbe@example.com', $user->email);
        $this->assertEquals('+659774123', $user->phone);
        $this->assertEquals('female', $user->gender);
        $this->assertEquals('SG', $user->country_code);
        $this->assertNotEquals($company->id, $user->company_id);
    }

    public function test_delete_user()
    {
        $user = factory(User::class)->create();
        $this->assertInstanceOf(User::class, $user);
        $user = $user->delete();

        $this->assertEquals('true', json_encode($user));
    }
}
