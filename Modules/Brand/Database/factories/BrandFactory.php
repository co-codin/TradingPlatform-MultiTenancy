<?php

namespace Modules\Brand\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Brand\Models\Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;

        $uniqueName = str_replace('-', '_', Str::slug($name));

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

