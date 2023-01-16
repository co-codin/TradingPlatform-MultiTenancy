<?php

namespace Modules\Customer\Database\factories;

use Database\Factories\BaseFactory;
use Illuminate\Support\Facades\Hash;
use Modules\Campaign\Models\Campaign;
use Modules\Currency\Models\Currency;
use Modules\Customer\Enums\Gender;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\Sale\Models\SaleStatus;
use Modules\User\Models\User;
use Spatie\Multitenancy\Landlord;
use Spatie\Multitenancy\Models\Tenant;

class CustomerFactory extends BaseFactory
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
    public function definition(): array
    {
        $tenant = Tenant::current();

        $data = array_merge(
            $this->getTenantData(),
            Landlord::execute(function () {
                return $this->getLandlordData();
            }),
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
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(Gender::getValues()),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'phone' => $this->faker->e164PhoneNumber(),
            'phone2' => $this->faker->e164PhoneNumber(),

            'country_id' => Country::inRandomOrder()->first(),
            'language_id' => Language::factory(),
            'currency_id' => Currency::inRandomOrder()->first(),
            'department_id' => Department::inRandomOrder()->first(),
            'desk_id' => Desk::factory(),

            'conversion_sale_status_id' => SaleStatus::factory(),
            'retention_sale_status_id' => SaleStatus::factory(),

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

    /**
     * Get landlord data.
     *
     * @return array
     */
    private function getLandlordData(): array
    {
        return [
            'campaign_id' => Campaign::factory(),
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
