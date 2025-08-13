<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'        => $this->faker->title,
            'firstname'    => $this->faker->firstName,
            'lastname'     => $this->faker->lastName,
            'company'      => $this->faker->company,
            'street'       => $this->faker->streetAddress,
            'city'         => $this->faker->city,
            'town'         => $this->faker->citySuffix,
            'county'       => $this->faker->city,
            'postcode'     => $this->faker->postcode,
            'country'      => 'United Kingdom',
            'phone'        => $this->faker->phoneNumber,
            'mobile_phone' => $this->faker->phoneNumber,
            'work_phone'   => $this->faker->phoneNumber,
            'fax'          => $this->faker->phoneNumber,
            'email1'       => 'info@mail.dev',
            'email2'       => 'info@mail.dev',
            'email3'       => 'info@mail.dev',
            'note'         => $this->faker->text(200),
            'web_page'     => $this->faker->domainName,
        ];
    }
}
