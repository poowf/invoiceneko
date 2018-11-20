<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class BouncerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bouncer::allow('superadmin')->everything();

        Bouncer::allow('companyadmin')->everything();
        Bouncer::forbid('companyadmin')->toManage(User::class);
    }
}
