<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'       => $this->faker->company,
            'address'    => $this->faker->address,
            'telephone1' => $this->faker->phoneNumber,
            'telephone2' => $this->faker->phoneNumber,
            'web'        => $this->faker->url,
            'email'      => $this->faker->email,
            'bank'       => $this->faker->name,
            'sort_code'  => $this->faker->randomNumber(),
            'account_nr' => $this->faker->randomNumber(),
            'iban'       => $this->faker->randomNumber(),
            'vat_number' => $this->faker->randomNumber(),
            'swift'      => $this->faker->swiftBicNumber,
            'disclaimer' => $this->faker->paragraph,
        ];
    }
}
