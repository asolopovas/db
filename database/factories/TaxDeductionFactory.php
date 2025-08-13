<?php

namespace Database\Factories;

use App\Models\TaxDeduction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxDeductionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaxDeduction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name' => $this->faker->word,
        ];
    }
}
