<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     * @throws \Throwable
     */
    public function test_sign_in()
    {
        $user = factory(User::class)->create([
            'email' => 'test@poowf.com',
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/signin')
                ->type('username', $user->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/errors/nocompany');
        });
    }
}
