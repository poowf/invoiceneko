<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Company::class, function (Faker $faker) {
    return [
        'name'          => $faker->company,
        'invoice_index' => $faker->randomDigit,
        'quote_index'   => $faker->randomDigit,
        'slug'          => $faker->slug,
        'domain_name'   => str_random($faker->numberBetween($min = 1, $max = 63)) . $faker->randomElement(['.com', '.net', '.org']),
        'crn'           => $faker->ean8,
        'country_code'  => $faker->countryCode,
        'timezone'      => $faker->timezone,
        'phone'         => '+659' . $faker->numberBetween($min = 0, $max = 8) . $faker->randomNumber(6, true),
        'email'         => $faker->unique()->companyEmail,
        'user_id'       => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
    ];
});


