<?php

namespace Modules\Communication\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Communication\Models\ChatMessage;
use Modules\Customer\Models\Customer;
use Modules\User\Models\User;
use Spatie\Multitenancy\Models\Tenant;

class ChatMessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ChatMessage::class;

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
            'customer_id' => $customer = Customer::factory(),
            'message' => $this->faker->sentence(3),
            'initiator' => $customer->id,
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
