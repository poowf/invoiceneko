<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\QuoteItem::class, function (Faker $faker) {
    return [
        'name' => $faker->bs(),
        'quantity' => $faker->numberBetween($min = 1, $max = 1000),
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = NULL),
        'description' => $faker->text(200),
        'quote_id' => function () {
            return factory(\App\Models\Quote::class)->create()->id;
        }
    ];
});
