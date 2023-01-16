<?php

declare(strict_types=1);

namespace Modules\Config\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Config\Enums\ConfigDataTypeEnum;
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
            'config_type_id' => ConfigType::factory(),
            'data_type' => $type = $this->faker->randomElement(ConfigDataTypeEnum::getValues()),
            'name' => $this->faker->sentence(3),
            'value' => match ($type) {
                ConfigDataTypeEnum::JSON => json_encode(array_flip($this->faker->words()), JSON_THROW_ON_ERROR),
                ConfigDataTypeEnum::INTEGER => $this->faker->numberBetween(),
                ConfigDataTypeEnum::STRING => $this->faker->word,
                ConfigDataTypeEnum::BOOLEAN => $this->faker->boolean,
            },
        ];
    }
}
