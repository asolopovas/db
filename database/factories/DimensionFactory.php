<?php

namespace Database\Factories;

use App\Models\Dimension;
use Illuminate\Database\Eloquent\Factories\Factory;

class DimensionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Dimension::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'width'     => $this->faker->randomElement([180, 220, 240, 300]),
            'length'    => $this->faker->randomElement([0, 350, 600, 450, 400]),
            'thickness' => $this->faker->randomElement([16, 21]),
            'type'      => $this->faker->randomElement(['Plank', 'Herringbone', 'Chevron']),
            'price'     => $this->faker->randomElement([0, 5, 10, 15]),
        ];
    }
}
