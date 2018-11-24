<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\InvoiceRecurrence::class, function (Faker $faker) {
    return [
        'time_interval' => '3',
        'time_period' => 'week',
        'until_type' => 'date',
        'until_meta' => '2020-10-31 00:00:00',
        'rule' => 'FREQ=WEEKLY;UNTIL=20201031T000000;INTERVAL=3',
        'company_id' => function () {
            return factory(\App\Models\Company::class)->create()->id;
        }
    ];
});
