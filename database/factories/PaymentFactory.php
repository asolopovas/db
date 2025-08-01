<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'order_id'    => Order::factory(),
            'amount'      => $this->faker->randomFloat(2, 100, 800),
            'description' => $this->faker->word,
            'date'        => Carbon::now(),
        ];
    }
}
