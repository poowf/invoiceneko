<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'nice_invoice_id' => $faker->slug,
        'date' => $faker->dateTime,
        'duedate' => $faker->dateTime,
        'netdays' => $faker->numberBetween($min = 1, $max = 60),
        'total' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
        'share_token' => $faker->uuid,
        'status' => $faker->numberBetween($min = 1, $max = 7),
        'archived' => $faker->boolean,
    ];
});