<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(\App\Models\CompanyUserRequest::class, function (Faker $faker) {
    return [
        'full_name'  => $faker->name,
        'email'      => $faker->unique()->safeEmail,
        'phone'      => '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true),
        'token'      => Str::random(10),
        'status'     => $faker->numberBetween($min = 1, $max = 3),
        'company_id' => function () {
            return factory(\App\Models\Company::class)->create()->id;
        },
    ];
});
