<?php

declare(strict_types=1);

namespace Modules\Media\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Media\Models\Attachment;

final class AttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'path' => $this->faker->filePath(),
        ];
    }
}
