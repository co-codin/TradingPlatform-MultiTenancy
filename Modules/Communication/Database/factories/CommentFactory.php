<?php

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communication\Models\Comment;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Spatie\Multitenancy\Landlord;
use Spatie\Multitenancy\Models\Tenant;

class CommentFactory extends Factory
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
            'user_id' => Landlord::execute(function () {
                return User::factory();
            }),
        ];
    }
}
