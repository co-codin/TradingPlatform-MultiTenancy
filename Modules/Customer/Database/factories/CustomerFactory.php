<?php

namespace Modules\Customer\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Enums\Gender;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
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
            'gender' => $this->faker->randomElement(Gender::getValues()),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber(),
            'phone2' => $this->faker->phoneNumber(),

            'affiliate_user_id' => User::factory(),
            'conversion_user_id' => $conversion = User::factory()->create(),
            'retention_user_id' => $retention = User::factory()->create(),
            'compliance_user_id' => User::factory(),
            'support_user_id' => User::factory(),
            'conversion_manager_user_id' => User::factory(),
            'retention_manager_user_id' => User::factory(),
            'first_conversion_user_id' => $conversion,
            'first_retention_user_id' => $retention,

            'country_id' => Country::factory(),
            'language_id' => Language::factory(),
            'department_id' => Department::factory(),
            'desk_id' => Desk::factory(),

            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'offer_name' => $this->faker->sentence(3),
            'offer_url' => $this->faker->url(),
            'comment_about_customer' => $this->faker->sentence(20),
            'source' => $this->faker->sentence(5),
            'click_id' => $this->faker->lexify(),
            'free_param_1' => $this->faker->sentence(1),
            'free_param_2' => $this->faker->sentence(1),
            'free_param_3' => $this->faker->sentence(1),
        ];
    }
}
