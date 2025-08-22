<?php

namespace Database\Factories;

use App\Models\OrderService;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderService::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $service = Service::factory()->create();

        return [
            'order_id'   => null,
            'service_id' => $service->id,
            'name'       => $service->name,
            'quantity'   => $this->faker->numberBetween(1, 20),
            'unit_price' => $service->price,
        ];
    }
}
