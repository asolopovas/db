<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'name'           => $this->faker->name,
            'username'       => $this->faker->userName(),
            'role_id'        => Role::factory(),
            'email'          => $this->faker->safeEmail,
            'phone'          => $this->faker->phoneNumber,
            'mobile'         => $this->faker->phoneNumber,
            'password'       => bcrypt(str_random(10)),
            'remember_token' => str_random(10),
        ];
    }
}
