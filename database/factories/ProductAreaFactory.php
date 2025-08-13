<?php

namespace Database\Factories;

use App\Models\Area;
use App\Models\ProductArea;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductAreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductArea::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'area_id'    => Area::factory(),
            'product_id' => null,
            'meterage'   => $this->faker->numberBetween(20, 100),
        ];
    }
}
