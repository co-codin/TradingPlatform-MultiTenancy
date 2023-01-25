<?php

namespace Modules\Customer\Database\factories;

use Database\Factories\BaseFactory;
use Illuminate\Support\Facades\Hash;
use libphonenumber\PhoneNumberUtil;
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
use Propaganistas\LaravelPhone\Exceptions\CountryCodeException;
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
            'first_name' => $this->faker->unique()->regexify('[a-zA-Z]{8,20}'),
            'last_name' => $this->faker->unique()->regexify('[a-zA-Z]{8,20}'),
            'gender' => $this->faker->randomElement(Gender::getValues()),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
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
     *
     * @throws CountryCodeException
     */
    private function getLandlordData(): array
    {
        $supportedCountriesForPhones = PhoneNumberUtil::getInstance()->getSupportedRegions();
        $country = Country::inRandomOrder()->whereIn('iso2', $supportedCountriesForPhones)->first()
            ?? Country::factory()->create(['iso2', $this->faker->randomElement($supportedCountriesForPhones)]);
        $phone = PhoneNumberUtil::getInstance()->getExampleNumber($country->iso2);
        $phone2 = PhoneNumberUtil::getInstance()->getExampleNumber($country->iso2);

        return [
            'phone' => phone($phone->getCountryCode().$phone->getNationalNumber(), Country::pluck('iso2')->toArray())->formatE164(),
            'phone2' => phone($phone2->getCountryCode().$phone->getNationalNumber(), Country::pluck('iso2')->toArray())->formatE164(),
            'currency_id' => Currency::inRandomOrder()->first() ?? Currency::factory(),
            'language_id' => Language::inRandomOrder()->first() ?? Language::factory(),
            'country_id' => $country,
            'campaign_id' => Campaign::inRandomOrder()->where('is_active', true)->first() ?? Campaign::factory(['is_active' => true]),
            'affiliate_user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'conversion_user_id' => $conversion = User::inRandomOrder()->first() ?? User::factory()->create(),
            'retention_user_id' => $retention = User::inRandomOrder()->first() ?? User::factory()->create(),
            'compliance_user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'support_user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'conversion_manager_user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'retention_manager_user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'first_conversion_user_id' => $conversion,
            'first_retention_user_id' => $retention,
        ];
    }
}
