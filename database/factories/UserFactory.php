<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\User::class, function (Faker $faker) {
    static $password;

    return [
        'full_name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'country_code' => $faker->countryCode,
        'timezone' => $faker->timezone,
        'password' => $password ?: $password = 'secret',
        'phone' => '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true),
        'gender' => 'male'|'female',
        'remember_token' => str_random(10),
        'status' => $faker->numberBetween($min = 1, $max = 3)
    ];
});
