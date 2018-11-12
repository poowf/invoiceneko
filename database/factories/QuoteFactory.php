<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Quote::class, function (Faker $faker) {
    return [
        'nice_quote_id' => substr($faker->slug, 0, 20) . 'sasdf',
        'date' => $faker->dateTime,
        'duedate' => $faker->dateTime,
        'netdays' => $faker->numberBetween($min = 1, $max = 60),
        'total' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999999.99),
        'share_token' => $faker->uuid,
        'status' => $faker->numberBetween($min = 1, $max = 7),
        'archived' => $faker->boolean,
        'client_id' => function () {
            return factory(\App\Models\Client::class)->create()->id;
        },
        'company_id' => function () {
            return factory(\App\Models\Company::class)->create()->id;
        }
    ];
});
