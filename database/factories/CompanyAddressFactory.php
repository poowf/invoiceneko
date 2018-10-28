<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\CompanyAddress::class, function (Faker $faker) {
    return [
        'block' => $faker->buildingNumber,
        'street' => $faker->streetName,
        'unitnumber' => $faker->buildingNumber,
        'postalcode' => $faker->postcode,
        'buildingtype' => $faker->numberBetween($min = 1, $max = 2),
        'company_id' => function () {
            return factory(\App\Models\Company::class)->create()->id;
        }
    ];
});
