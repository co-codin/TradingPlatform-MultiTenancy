<?php

namespace Modules\Customer\Database\factories;

use Database\Factories\BaseFactory;
use Illuminate\Support\Facades\Hash;
use libphonenumber\PhoneNumberUtil;
use Modules\Campaign\Models\Campaign;
use Modules\Currency\Models\Currency;
use Modules\Customer\Enums\CustomerVerificationStatus;
use Modules\Customer\Enums\ForbiddenCountry;
use Modules\Customer\Enums\Gender;
use Modules\Customer\Models\Customer;
use Modules\Department\Models\Department;
use Modules\Desk\Models\Desk;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Modules\Role\Enums\DefaultRole;
use Modules\Sale\Enums\SaleStatusNameEnum;
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

    private ?Department $department = null;

    /**
     * Define the model's default state.
     *
     * @return array
     *
     * @throws CountryCodeException
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
            'email_2' => $this->faker->safeEmail(),
            'password' => Hash::make('password'),
            'department_id' => $this->department = Department::inRandomOrder()->first(),
            'desk_id' => Desk::factory(),

            'conversion_sale_status_id' => $this->department?->isConversion() ? SaleStatus::inRandomOrder()
                ->whereIn('name', SaleStatusNameEnum::conversionSaleStatusList())
                ->first()?->id : null,
            'retention_sale_status_id' => $this->department?->isRetention() ? SaleStatus::inRandomOrder()
                ->whereIn('name', SaleStatusNameEnum::retentionSaleStatusList())
                ->first()?->id : null,

            'verification_status' => $this->faker->randomElement(CustomerVerificationStatus::getValues()),
            'city' => $this->faker->city(),
            'address' => $this->faker->address(),
            'postal_code' => $this->faker->postcode(),
            'offer_name' => $this->faker->text(35),
            'offer_url' => $this->faker->url(),
            'comment_about_customer' => $this->faker->sentence(20),
            'source' => $this->faker->text(35),
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
        $supportedCountriesForPhones = array_diff(PhoneNumberUtil::getInstance()->getSupportedRegions(), ForbiddenCountry::getValues());
        $country = Country::inRandomOrder()->whereIn('iso2', $supportedCountriesForPhones)->first()
            ?: Country::factory()->create(['iso2' => $this->faker->randomElement($supportedCountriesForPhones)]);
        $phone = PhoneNumberUtil::getInstance()->getExampleNumber($country->iso2);
        $phone2 = PhoneNumberUtil::getInstance()->getExampleNumber($country->iso2);

        $data = [
            'phone' => $phone->getCountryCode() . $phone->getNationalNumber(),
            'phone_2' => $phone2->getCountryCode() . $phone2->getNationalNumber(),
            'currency_id' => Currency::inRandomOrder()->first() ?? Currency::factory(),
            'language_id' => Language::inRandomOrder()->first() ?? Language::factory(),
            'country_id' => $country,
            'campaign_id' => Campaign::inRandomOrder()->where('is_active', true)->first() ?? Campaign::factory(['is_active' => true]),
            'affiliate_user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'compliance_user_id' => User::inRandomOrder()->first() ?? User::factory(),
            'support_user_id' => User::inRandomOrder()->first() ?? User::factory(),
        ];

        if ($this->department?->isConversion()) {
            $data['conversion_user_id'] = $conversion = User::inRandomOrder()
                ->whereHas('roles', fn ($q) => $q->where('name', DefaultRole::CONVERSION_AGENT))
                ->first();

            $data['conversion_manager_user_id'] = User::inRandomOrder()
                ->whereHas('roles', fn ($q) => $q->where('name', DefaultRole::CONVERSION_MANAGER))
                ->first();

            $data['first_conversion_user_id'] = $conversion;
        }

        if ($this->department?->isRetention()) {
            $data['retention_user_id'] = $retention = User::inRandomOrder()
                ->whereHas('roles', fn ($q) => $q->where('name', DefaultRole::RETENTION_AGENT))
                ->first();

            $data['retention_manager_user_id'] = User::inRandomOrder()
                ->whereHas('roles', fn ($q) => $q->where('name', DefaultRole::RETENTION_MANAGER))
                ->first();

            $data['first_retention_user_id'] = $retention;
        }

        return $data;
    }
}
