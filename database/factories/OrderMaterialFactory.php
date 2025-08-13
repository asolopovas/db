<?php

namespace Database\Factories;

use App\Models\Material;
use App\Models\OrderMaterial;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderMaterialFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $material = Material::factory()->create();

        return [
            'order_id'    => null,
            'material_id' => $material->id,
            'quantity'    => $this->faker->numberBetween(1, 20),
            'unit_price'  => $material->price,
        ];
    }
}
