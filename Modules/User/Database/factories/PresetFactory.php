<?php

namespace Modules\User\Database\factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\Preset;
use Modules\User\Models\User;

class PresetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Preset::class;

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
        ];
    }
}
