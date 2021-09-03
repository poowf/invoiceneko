<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\InvoiceRecurrence;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceRecurrenceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvoiceRecurrence::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'time_interval' => '3',
            'time_period'   => 'week',
            'until_type'    => 'date',
            'until_meta'    => '2020-10-31 00:00:00',
            'rule'          => 'FREQ=WEEKLY;UNTIL=20201031T000000;INTERVAL=3',
            'company_id'    => function () {
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
