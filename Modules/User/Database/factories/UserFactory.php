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
            'username' => $this->faker->unique()->regexify('[a-zA-Z0-9]{8,20}'),
            'password' => Hash::make('Password%12345'),
            'remember_token' => Str::random(10),
            'is_active' => true,
            'target' => $this->faker->randomNumber(),
            'parent_id' => null,
            'affiliate_id' => null,
            'show_on_scoreboards' => $this->faker->boolean(),
        ];
    }

    /**
     * Add parent id.
     *
     * @return $this
     */
    public function withParent(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => User::factory()->create()?->id,
            ];
        });
    }

    /**
     * Add affiliate id.
     *
     * @return $this
     */
    public function withAffiliate(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'affiliate_id' => User::factory()->create()?->id,
            ];
        });
    }
}
