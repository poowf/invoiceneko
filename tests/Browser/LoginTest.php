<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @throws \Throwable
     *
     * @return void
     */
    public function test_sign_in()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser
                ->visit('/signin')
                ->type('username', $user->email)
                ->type('password', 'secret')
                ->press('SIGN IN')
                ->assertPathIs('/errors/nocompany');
        });
    }
}
