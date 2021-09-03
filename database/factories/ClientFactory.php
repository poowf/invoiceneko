<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'companyname'       => $this->faker->company,
            'phone'             => '+659'.$this->faker->numberBetween($min = 0, $max = 8).$this->faker->randomNumber(6, true),
            'block'             => $this->faker->buildingNumber,
            'street'            => $this->faker->streetName,
            'unitnumber'        => $this->faker->buildingNumber,
            'postalcode'        => $this->faker->postcode,
            'country_code'      => $this->faker->countryCode,
            'nickname'          => $this->faker->name,
            'crn'               => $this->faker->ean8,
            'website'           => $this->faker->url,
            'contactsalutation' => $this->faker->title,
            'contactfirstname'  => $this->faker->firstName,
            'contactlastname'   => $this->faker->lastName,
            'contactgender'     => 'male',
            'contactemail'      => $this->faker->unique()->companyEmail,
            'contactphone'      => '+658'.$this->faker->numberBetween($min = 1, $max = 8).$this->faker->randomNumber(6, true),
            'company_id'        => function () {
                return Company::factory()->create()->id;
            },
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
            ];
        });
    }
}
