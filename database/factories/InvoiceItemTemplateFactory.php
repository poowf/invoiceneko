<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\InvoiceItemTemplate::class, function (Faker $faker) {
    return [
        'name' => $faker->bs(),
        'quantity' => $faker->numberBetween($min = 1, $max = 999999999),
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999),
        'description' => $faker->text(200),
        'invoice_template_id' => function () {
            return factory(\App\Models\InvoiceTemplate::class)->create()->id;
        }
    ];
});
