<?php

namespace Modules\Customer\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

class CustomerChatMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerChatMessage::class;

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
            'customer_id' => $customer = Customer::factory()->create(),
            'message' => $this->faker->sentence(3),
            'initiator_id' => $customer->id,
            'initiator_type' => 'customer',
            'read' => 0,
        ];
    }

    /**
     * Get landlord data.
     *
     * @return array
     */
    private function getLandlordData(): array
    {
        return [
            'user_id' => User::factory(),
        ];
    }
}
