<?php

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\User\Models\User;

final class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->regexify('[a-zA-Z0-9]{8,20}'),
            'first_name' => $this->faker->unique()->regexify('[a-zA-Z0-9]{8,20}'),
            'last_name' => $this->faker->unique()->regexify('[a-zA-Z0-9]{8,20}'),
            'email' => $this->faker->unique()->email,
            'password' => Hash::make('Password%'),
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
            if (! $admin = User::getAdmin()) {
                $admin = User::factory()->create();
                $admin->roles()->sync(
                    Role::where('name', DefaultRole::ADMIN)->first() ??
                    Role::factory()->create(['name' => DefaultRole::ADMIN])
                );
            }

            return [
                'parent_id' => $admin->id,
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
            if (! $admin = User::getAdmin()) {
                $admin = User::factory()->create();
                $admin->roles()->sync(
                    Role::where('name', DefaultRole::ADMIN)->first() ??
                    Role::factory()->create(['name' => DefaultRole::ADMIN])
                );
            }

            ! Role::where([
                ['name', '=', DefaultRole::AFFILIATE],
                ['guard_name', '=', 'api'],
            ])->exists() && Role::factory()->create([
                'name' => DefaultRole::AFFILIATE,
                'guard_name' => 'api',
            ]);

            return [
                'affiliate_id' => $admin->id,
            ];
        });
    }
}
