<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\CompanyUserRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyUserRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyUserRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name'  => $this->faker->name,
            'email'      => $this->faker->unique()->safeEmail,
            'phone'      => '+658' . $this->faker->numberBetween($min = 1, $max = 8) . $this->faker->randomNumber(6, true),
            'token'      => Str::random(10),
            'status'     => $this->faker->numberBetween($min = 1, $max = 3),
            'company_id' => function () {
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
