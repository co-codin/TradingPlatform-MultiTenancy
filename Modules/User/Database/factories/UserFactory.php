<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\User\Models\User;

final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->userName,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->email,
            'password' => Hash::make('admin'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'target' => $this->faker->randomNumber(),
            'parent_id' => null,
        ];
    }

    public function withParent(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => User::inRandomOrder()->first()?->id,
            ];
        });
    }
}
