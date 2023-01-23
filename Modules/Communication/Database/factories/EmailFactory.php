<?php

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communication\Models\Email;
use Modules\Communication\Models\EmailTemplates;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

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
        $tenant = Tenant::current();

        $data = array_merge(
            $this->getTenantData(),
            $this->getLandlordData(),
        );

        $tenant->makeCurrent();

        return $data;
    }

    /**
     * Get tenant data.
     *
     * @return array
     */
    private function getTenantData(): array
    {
        return [
            'email_template_id' => EmailTemplates::factory(),
            'subject' => $this->faker->sentence(3),
            'body' => $this->faker->sentence(15),
            'sent_by_system' => $this->faker->boolean,
        ];
    }

    /**
     * Get landlord data.
     *
     * @return array
     */
    private function getLandlordData(): array
    {
        $owner = User::factory()->create();

        return [
            'user_id' => $user = User::inRandomOrder()->first() ?? User::factory()->create(),
            'sendemailable_type' => get_class($owner),
            'sendemailable_id' => $owner->id,
            'emailable_type' => get_class($user),
            'emailable_id' => $user->id,
        ];
    }
}
