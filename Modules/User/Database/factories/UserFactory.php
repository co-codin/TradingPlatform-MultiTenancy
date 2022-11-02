<?php
declare(strict_types=1);

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name . ' ' . $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => bcrypt('admin1'),
            'remember_token' => Str::random(10),
        ];
    }
}
