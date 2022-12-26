<?php

declare(strict_types=1);

namespace Modules\Communication\Database\factories;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Communication\Models\DatabaseNotification;
use Modules\User\Models\User;

final class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DatabaseNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid()->toString(),
            'type' => DatabaseNotification::class,
            'data' => $this->faker->text,
            'user_id' => User::inRandomOrder()->first() ?? User::factory()->create(),
            'read_at' => $this->faker->boolean()
                ? CarbonImmutable::now()->addMinutes($this->faker->randomNumber(2)) : null,
        ];
    }

    public function forModel(Model $model): self
    {
        return $this->state(fn () => [
            'notifiable_type' => $model->getMorphClass(),
            'notifiable_id' => $model->id,
        ]);
    }
}
