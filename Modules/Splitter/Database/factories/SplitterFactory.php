<?php

declare(strict_types=1);

namespace Modules\Splitter\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Brand\Models\Brand;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Models\Splitter;

final class SplitterFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Splitter::class;

    private static $position = 0;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $brand_id = (Brand::first() ?? Brand::factory()->create())->id;

        if (self::$position == 0) {
            self::$position = Splitter::whereBrandId($brand_id)->whereIsActive(true)->max('position') ?? 0;
        }

        self::$position++;

        return [
            'brand_id' => $brand_id,
            'name' => $this->faker->sentence(3),
            'is_active' => true,
            'position' => self::$position,
        ];
    }

    public function addSplitterChoiceData()
    {
        return $this->state(function (array $attributes) {
            return [
                'splitter_choice' => [
                    'type' => $this->faker->randomElement(SplitterChoiceType::getValues()),
                    'option_per_day' => $this->faker->randomElement(SplitterChoiceOptionPerDay::getValues()),
                ],
            ];
        });
    }
}
