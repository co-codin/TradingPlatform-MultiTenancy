<?php

declare(strict_types=1);

namespace Modules\Brand\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Brand\Models\Brand;

final class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->name;

        $uniqueName = Str::slug($name, '_');

        return [
            'name' => $name,
            'title' => $this->faker->title,
            'domain' => $uniqueName,
            'database' => $uniqueName,
            'logo_url' => $this->faker->imageUrl,
            'is_active' => true,
        ];
    }
}
