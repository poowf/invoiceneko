<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'amount' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999),
        'receiveddate' => $faker->dateTime,
        'mode' => 'Cheque'|'Bank Transfer',
        'notes' => $faker->realText(50, 2),
        'invoice_id' => function () {
            return factory(\App\Models\Invoice::class)->create()->id;
        },
        'client_id' => function () {
            return factory(\App\Models\Client::class)->create()->id;
        },
        'company_id' => function () {
            return factory(\App\Models\Company::class)->create()->id;
        }
    ];
});
