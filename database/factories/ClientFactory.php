<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Client::class, function (Faker $faker) {
    return [
        'companyname'       => $faker->company,
        'phone'             => '+659' . $faker->numberBetween($min = 0, $max = 8) . $faker->randomNumber(6, true),
        'block'             => $faker->buildingNumber,
        'street'            => $faker->streetName,
        'unitnumber'        => $faker->buildingNumber,
        'postalcode'        => $faker->postcode,
        'country_code'      => $faker->countryCode,
        'nickname'          => $faker->name,
        'crn'               => $faker->ean8,
        'website'           => $faker->url,
        'contactsalutation' => $faker->title,
        'contactfirstname'  => $faker->firstName,
        'contactlastname'   => $faker->lastName,
        'contactgender'     => 'male',
        'contactemail'      => $faker->unique()->companyEmail,
        'contactphone'      => '+658' . $faker->numberBetween($min = 1, $max = 8) . $faker->randomNumber(6, true),
        'company_id'        => function () {
            return factory(\App\Models\Company::class)->create()->id;
        },
    ];
});
