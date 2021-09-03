<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\ItemTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->bs(),
            'quantity'    => $this->faker->numberBetween($min = 1, $max = 999999999),
            'price'       => $this->faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 999999999999),
            'description' => $this->faker->text(200),
            'company_id'  => function () {
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
