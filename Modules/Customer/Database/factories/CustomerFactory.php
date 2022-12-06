<?php

namespace Modules\Customer\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Models\Customer;
use Modules\Geo\Models\Country;
use Modules\User\Models\User;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'gender' => $this->faker->numberBetween(1, 2),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber(),
            'country_id' => $this->faker->randomElement(Country::pluck('id')),
            'affiliate_user_id' => User::factory(),
            'conversion_user_id' => $conversion = User::factory()->create(),
            'retention_user_id' => $retention = User::factory()->create(),
            'compliance_user_id' => User::factory(),
            'support_user_id' => User::factory(),
            'conversion_manager_user_id' => User::factory(),
            'retention_manager_user_id' => User::factory(),
            'first_conversion_user_id' => $conversion,
            'first_retention_user_id' => $retention,
        ];
    }
}
