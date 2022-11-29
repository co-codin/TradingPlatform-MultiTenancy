<?php

declare(strict_types=1);

namespace Modules\Config\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Config\Enums\DataType;
use Modules\Config\Models\Config;
use Modules\Config\Models\ConfigType;

final class ConfigFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Config::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    final public function definition(): array
    {
        return [
            'config_type_id' => ConfigType::factory()->create(),
            'data_type' => $type = $this->faker->randomElement(DataType::getValues()),
            'name' => $this->faker->name(),
            'value' => $type == 'json' ? json_encode([]) : $this->faker->name(),
        ];
    }
}

