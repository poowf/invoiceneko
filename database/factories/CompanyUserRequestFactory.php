<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\CompanyUserRequest::class, function (Faker $faker) {
    return [
        'full_name'  => $faker->name,
        'email'      => $faker->unique()->safeEmail,
        'phone'      => '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true),
        'token'      => str_random(10),
        'status'     => $faker->numberBetween($min = 1, $max = 3),
        'company_id' => function () {
            return factory(\App\Models\Company::class)->create()->id;
        },
    ];
});
