<?php

namespace Modules\Worker\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Worker\Models\Worker;

class WorkerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Worker::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => bcrypt('admin'),
            'remember_token' => Str::random(10),
            'is_active' => true,
        ];
    }
}

