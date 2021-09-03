<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name'         => $this->faker->name,
            'username'          => $this->faker->userName,
            'email'             => $this->faker->unique()->safeEmail,
            'country_code'      => $this->faker->countryCode,
            'timezone'          => $this->faker->timezone,
            'password'          => 'secret',
            'phone'             => '+658' . $this->faker->numberBetween($min = 1, $max = 8) . $this->faker->randomNumber(6, true),
            'gender'            => 'male' | 'female',
            'remember_token'    => Str::random(10),
            'status'            => $this->faker->numberBetween($min = 1, $max = 3),
            'email_verified_at' => now(),
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
                'email_verified_at' => null,
            ];
        });
    }
}
