<?php

declare(strict_types=1);

namespace Modules\Brand\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Brand\Models\Brand;
use Modules\Brand\Models\UserBrand;
use Modules\User\Models\User;

final class UserBrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserBrand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'user_id' => User::factory(),
            'is_default' => false,
        ];
    }
}
