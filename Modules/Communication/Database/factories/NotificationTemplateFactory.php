<?php

declare(strict_types=1);

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Communication\Models\NotificationTemplate;
use Modules\User\Models\User;

final class NotificationTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NotificationTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => Str::uuid()->toString(),
            'data' => [
                'subject' => $this->faker->sentence,
                'text' => $this->faker->text,
            ],
            'user_id' => User::inRandomOrder()->first() ?? User::factory()->create(),
        ];
    }
}
