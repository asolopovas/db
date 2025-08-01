<?php

namespace Database\Factories;

use App\Models\Dimension;
use App\Models\Extra;
use App\Models\Floor;
use App\Models\Grade;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $floor = Floor::factory()->create();
        $extra = Extra::factory()->create();
        $dimension = Dimension::factory()->create();
        $grade = Grade::factory()->create();
        $unit_price = $floor->price + $extra->price + $dimension->price + $grade->price;
        $meterage = $this->faker->randomElement([20, 40, 60, 100, 120]);
        $wastage_rate = $this->faker->randomElement([7.5, 10]);
        $discount = $this->faker->numberBetween(0, 10);

        return [
            'order_id'     => null,
            'floor_id'     => $floor->id,
            'extra_id'     => $extra->id,
            'dimension_id' => $dimension->id,
            'grade_id'     => $grade->id,
            'discount'     => $discount,
            'wastage_rate' => $wastage_rate,
            'unit_price'   => $unit_price,
            'meterage'     => $meterage,
        ];
    }
}
