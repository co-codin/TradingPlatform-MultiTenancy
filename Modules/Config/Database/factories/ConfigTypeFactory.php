<?php

declare(strict_types=1);

namespace Modules\Config\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Config\Models\ConfigType;
use Modules\Config\Enums\ConfigType as ConfigTypeEnum;

final class ConfigTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConfigType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    final public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(ConfigTypeEnum::getValues()),
        ];
    }
}

