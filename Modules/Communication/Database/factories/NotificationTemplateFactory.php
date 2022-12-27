<?php

declare(strict_types=1);

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
            'data' => [
                'subject' => $this->faker->sentence,
                'text' => $this->faker->text,
            ],
            'creator_id' => User::inRandomOrder()->first() ?? User::factory()->create(),
        ];
    }
}
