<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\Company;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nice_quote_id' => substr($this->faker->slug, 0, 20) . 'sasdf',
            'date'          => $this->faker->dateTime,
            'duedate'       => $this->faker->dateTime,
            'netdays'       => $this->faker->numberBetween($min = 1, $max = 60),
            'total'         => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999999.99),
            'share_token'   => $this->faker->uuid,
            'status'        => $this->faker->numberBetween($min = 1, $max = 7),
            'archived'      => $this->faker->boolean,
            'client_id'     => function () {
                return Client::factory()->create()->id;
            },
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
