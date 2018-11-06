<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\InvoiceItem::class, function (Faker $faker) {
    return [
        'name' => $faker->bs(),
        'quantity' => $faker->numberBetween($min = 1, $max = 1000),
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = NULL),
        'description' => $faker->text(200),
        'invoice_id' => function () {
            return factory(\App\Models\Invoice::class)->create()->id;
        }
    ];
});
