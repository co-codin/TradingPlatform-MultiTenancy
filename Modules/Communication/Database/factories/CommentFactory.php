<?php

namespace Modules\Communication\Database\factories;

use Database\Factories\BaseFactory;
use Modules\Communication\Models\Comment;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;

class CommentFactory extends BaseFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->realText(),
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
        ];
    }
}
