<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Client::class, function (Faker $faker) {

    return [
        'companyname' => $faker->company,
        'phone' => '+65' . $faker->randomNumber(8),
        'block' => $faker->buildingNumber,
        'street' => $faker->streetName,
        'unitnumber' => $faker->buildingNumber,
        'postalcode' => $faker->postcode,
        'country' => $faker->country,
        'nickname' => $faker->name,
        'crn' => $faker->ean8,
        'website' => $faker->url,
        'contactsalutation' => $faker->title,
        'contactfirstname' => $faker->firstName,
        'contactlastname' => $faker->lastName,
        'contactgender' => 'male',
        'contactemail' => $faker->unique()->companyEmail,
        'contactphone' => '+65' . $faker->randomNumber(8),
        'company_id' => function () {
            return factory(\App\Models\Company::class)->create()->id;
        }
    ];
});
