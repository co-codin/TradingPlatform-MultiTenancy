<?php

namespace Modules\User\Database\factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;

class UserDisplayOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisplayOption::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'model_id' => Model::factory(),
            'name' => $this->faker->unique()->name(),
            'columns' => [],
            'settings' => [],
        ];
    }
}
