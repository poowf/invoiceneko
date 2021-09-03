<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\InvoiceRecurrence;
use App\Models\InvoiceTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'date'      => $this->faker->dateTime,
            'netdays'   => $this->faker->numberBetween($min = 1, $max = 60),
            'notify'    => $this->faker->boolean,
            'client_id' => function () {
                return Client::factory()->create()->id;
            },
            'invoice_recurrence_id' => function () {
                return InvoiceRecurrence::factory()->create()->id;
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
