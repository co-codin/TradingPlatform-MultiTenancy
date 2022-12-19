<?php

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communication\Models\Email;
use Modules\Communication\Models\EmailTemplates;
use Modules\User\Models\User;

class EmailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Email::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email_template_id' => EmailTemplates::factory(),
            'subject' => $this->faker->sentence(3),
            'body' => $this->faker->sentence(15),
            'sent_by_system' => $this->faker->boolean,
            'user_id' => User::factory(),
        ];
    }
}
