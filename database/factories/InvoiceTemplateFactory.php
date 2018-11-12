<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\InvoiceTemplate::class, function (Faker $faker) {
    return [
        'date' => $faker->dateTime,
        'netdays' => $faker->numberBetween($min = 1, $max = 60),
        'notify' => $faker->boolean,
        'client_id' => function () {
            return factory(\App\Models\Client::class)->create()->id;
        },
        'invoice_event_id' => function () {
            return factory(\App\Models\InvoiceEvent::class)->create()->id;
        }
    ];
});
