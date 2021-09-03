<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'          => $this->faker->company,
            'invoice_index' => $this->faker->randomDigit,
            'quote_index'   => $this->faker->randomDigit,
            'slug'          => $this->faker->slug,
            'domain_name'   => Str::random($this->faker->numberBetween($min = 1, $max = 63)).$this->faker->randomElement(['.com', '.net', '.org']),
            'crn'           => $this->faker->ean8,
            'country_code'  => $this->faker->countryCode,
            'timezone'      => $this->faker->timezone,
            'phone'         => '+659'.$this->faker->numberBetween($min = 0, $max = 8).$this->faker->randomNumber(6, true),
            'email'         => $this->faker->unique()->companyEmail,
            'user_id'       => function () {
                return User::factory()->create()->id;
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
