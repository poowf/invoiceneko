<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyAddress::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'block'        => $this->faker->buildingNumber,
            'street'       => $this->faker->streetName,
            'unitnumber'   => $this->faker->buildingNumber,
            'postalcode'   => $this->faker->postcode,
            'buildingtype' => $this->faker->numberBetween($min = 1, $max = 2),
            'company_id'   => function () {
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
