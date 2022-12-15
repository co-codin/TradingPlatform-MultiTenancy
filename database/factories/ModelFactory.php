<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Role\Services\ModelService;

final class ModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Model::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    final public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement(app(ModelService::class)->getModelPaths()),
        ];
    }
}
