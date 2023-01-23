<?php

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communication\Models\Call;
use Modules\Communication\Models\CommunicationProvider;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

class CallFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Call::class;

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
            'provider_id' => CommunicationProvider::factory(),
            'duration' => $this->faker->numberBetween(5, 60),
            'text' => $this->faker->sentence(15),
            'status' => $this->faker->numberBetween(1, 3),
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
            'sendcallable_type' => get_class($owner),
            'sendcallable_id' => $owner->id,
            'callable_type' => get_class($user),
            'callable_id' => $user->id,
        ];
    }
}
