<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'block' => $faker->buildingNumber,
        'street' => $faker->streetName,
        'unitnumber' => $faker->buildingNumber,
        'postalcode' => $faker->postcode,
        'buildingtype' => $faker->numberBetween($min = 1, $max = 2),
    ];
});
