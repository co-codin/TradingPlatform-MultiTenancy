<?php

namespace Tests\Feature\Modules\Customer\Admin\Affiliate;

use Illuminate\Support\Str;
use Modules\Currency\Models\Currency;
use Modules\Customer\Enums\CustomerPermission;
use Modules\Customer\Models\Customer;
use Modules\Geo\Models\Country;
use Modules\Language\Models\Language;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;
    /**
     * Test authorized user can create customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_customer(): void
    {
        $this->authenticateWithPermission(CustomerPermission::fromValue(CustomerPermission::CREATE_CUSTOMERS));

        $affiliateToken = $this->user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $data = Customer::factory()->make()->toArray();
        $this->brand->makeCurrent();

        $data['country'] = Country::find($data['country_id'])->name;
        $data['language'] = Language::find($data['language_id'])->name;
        $data['currency'] = Currency::find($data['currency_id'])->iso3;

        $response = $this->postJson(route('affiliate.customers.store'), $data);

        $response->assertCreated();

        $response->assertJsonStructure(['data' => [
            'password',
            'link',
        ]]);
    }

    /**
     * Test authorized user can`t create customer.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_cant_create_customer(): void
    {
        $this->authenticateUser();

        $affiliateToken = $this->user->affiliateToken()->create([
            'token' => Str::random(),
            'ip' => request()->ip(),
        ]);

        $this->withHeader('AffiliateToken', $affiliateToken->token);

        $this->brand->makeCurrent();

        $data = Customer::factory()->make()->toArray();
        $this->brand->makeCurrent();

        $data['country'] = Country::find($data['country_id'])->name;
        $data['language'] = Language::find($data['language_id'])->name;
        $data['currency'] = Currency::find($data['currency_id'])->iso3;

        $response = $this->postJson(route('affiliate.customers.store'), $data);

        $response->assertForbidden();
    }

    /**
     * Test unauthorized user can`t create customer.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized(): void
    {
        $this->brand->makeCurrent();

        $data = Customer::factory()->make()->toArray();
        $this->brand->makeCurrent();

        $response = $this->postJson(route('affiliate.customers.store'), $data);

        $response->assertUnauthorized();
    }
}
