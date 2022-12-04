<?php

namespace Modules\Customer\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;

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
            'phone2' => $this->faker->phoneNumber(),
            'language_id' => $this->faker->randomElement(Language::pluck('id')),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'desk_id' => $this->faker->randomElement(Desk::pluck('id')),
            'department_id' => $this->faker->randomElement(Department::pluck('id')),
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
