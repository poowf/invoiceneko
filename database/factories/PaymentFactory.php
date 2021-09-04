<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999),
            'receiveddate' => $this->faker->dateTime,
            'mode' => 'Cheque' | 'Bank Transfer',
            'notes' => $this->faker->realText(50, 2),
            'invoice_id' => function () {
                return Invoice::factory()->create()->id;
            },
            'client_id' => function () {
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
            return [];
        });
    }
}
