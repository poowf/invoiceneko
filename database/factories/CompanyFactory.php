<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'invoice_index' => $faker->randomDigit,
        'quote_index' => $faker->randomDigit,
        'slug' => $faker->slug,
        'crn' => $faker->ean8,
        'phone' => '+65' . $faker->randomNumber(8),
        'email' => $faker->unique()->companyEmail,
    ];
});
