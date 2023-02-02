<?php

declare(strict_types=1);

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;

final class WorkerInfoFactory extends Factory
{
    /**
     * {@inheritdoc}
     */
    protected $model = WorkerInfo::class;

    /**
     * {@inheritDoc}
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->unique()->regexify('[a-zA-Z]{8,20}'),
            'last_name' => $this->faker->unique()->regexify('[a-zA-Z]{8,20}'),
            'email' => $this->faker->unique()->email,
        ];
    }
}
