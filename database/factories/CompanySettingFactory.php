<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\CompanySetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanySettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanySetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice_prefix'     => $this->faker->domainWord,
            'quote_prefix'       => $this->faker->domainWord,
            'invoice_conditions' => $this->faker->realText($maxNbChars = 200, $indexSize = 2),
            'quote_conditions'   => $this->faker->realText($maxNbChars = 200, $indexSize = 2),
            'tax'                => $this->faker->numberBetween($min = 1, $max = 100),
            'company_id'         => function () {
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
