<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'invoice_index' => $faker->randomDigit,
        'quote_index' => $faker->randomDigit,
        'slug' => $faker->slug,
        'domain_name' => $faker->domainName,
        'crn' => $faker->ean8,
        'phone' => '+659' . $faker->numberBetween($min = 0, $max = 8) . $faker->randomNumber(6),
        'email' => $faker->unique()->companyEmail,
        'user_id' => function() {
            return factory(\App\Models\User::class)->create()->id;
        }
    ];
});
