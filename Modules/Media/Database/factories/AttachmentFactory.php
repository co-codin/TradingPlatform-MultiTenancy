<?php

declare(strict_types=1);

namespace Modules\Media\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communication\Models\Comment;
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
        $attachmentable = $this->faker->randomElement([
            Comment::class,
        ]);

        return [
            'path' => 'storage/customer-import/laravel-excel.xlsx',
            'attachmentable_id' => $attachmentable::factory(),
            'attachmentable_type' => $attachmentable,
        ];
    }
}
