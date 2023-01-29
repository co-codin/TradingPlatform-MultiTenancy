<?php

declare(strict_types=1);

namespace Modules\Splitter\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Models\SplitterChoice;

final class SplitterChoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SplitterChoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(SplitterChoiceType::getValues()),
            'option_per_day' => $this->faker->randomElement(SplitterChoiceOptionPerDay::getValues())
        ];
    }
}
