<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'name' => $faker->realText($maxNbChars = 20, $indexSize = 1),
        'quantity' => $faker->numberBetween($min = 1, $max = 1000),
        'price' => $faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = NULL),
        'description' => $faker->randomHtml(2,3),
    ];
});
