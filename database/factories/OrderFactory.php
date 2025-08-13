<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id'        => Customer::factory(),
            'company_id'         => Company::factory(),
            'status_id'          => Status::factory(),
            'user_id'            => User::factory(),
            'due'                => $this->faker->randomElement([30, 50, 100]),
            'vat'                => $this->faker->randomElement([0, 5, 15, 20]),
            'cc'                 => $this->faker->email,
            'mail_message'       => $this->faker->paragraph,
            'notes'              => $this->faker->paragraph,
            'sent'               => $this->faker->date(),
            'balance'            => "0.00",
            'discount'           => $this->faker->randomElement([1, 3, 5, 10]),
            'proforma'           => $this->faker->randomElement([true, false]),
            'proforma_message'   => $this->faker->paragraph(6),
            'proforma_breakdown' => $this->faker->boolean,
            'reverse_charge' => $this->faker->boolean,
            'payment_terms'      => "50% Deposit, 25% prior to Delivery, 25% on Completion",
            'payment_date'  => null,
        ];
    }
}
