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
        $area = Area::factory()->create();
        
        return [
            'area_id'    => $area->id,
            'product_id' => null,
            'name'       => $area->name,
            'meterage'   => $this->faker->numberBetween(20, 100),
            'price'      => $area->price ?? 0,
        ];
    }
}
