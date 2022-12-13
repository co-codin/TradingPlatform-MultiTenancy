<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Support\Str;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can create country.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make(static::demoData())->toArray();

        $response = $this->postJson(route('admin.countries.store'), $data);

        $response->assertCreated();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test unauthorized user can`t create country.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_create_country(): void
    {
        $this->brand->makeCurrent();

        $data = Country::factory()->make();

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test country name exist.
     *
     * @return void
     *
     * @test
     */
    public function country_name_exist(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());

        $data = Country::factory()->make(['name' => $country->name]);

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso2 exist.
     *
     * @return void
     *
     * @test
     */
    public function country_iso2_exist(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());

        $data = Country::factory()->make(['iso2' => $country->iso2]);

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso3 exist.
     *
     * @return void
     *
     * @test
     */
    public function country_iso3_exist(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());

        $data = Country::factory()->make(['iso3' => $country->iso3]);

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country name required.
     *
     * @return void
     *
     * @test
     */
    public function country_name_is_required(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->postJson(route('admin.countries.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country iso2 required.
     *
     * @return void
     *
     * @test
     */
    public function country_iso2_is_required(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make()->toArray();
        unset($data['iso2']);

        $response = $this->postJson(route('admin.countries.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country iso3 required.
     *
     * @return void
     *
     * @test
     */
    public function country_iso3_is_required(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make()->toArray();
        unset($data['iso3']);

        $response = $this->postJson(route('admin.countries.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country name is string.
     *
     * @return void
     *
     * @test
     */
    public function country_name_is_string(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make();
        $data->name = 1;

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso2 is string.
     *
     * @return void
     *
     * @test
     */
    public function country_iso2_is_string(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make();
        $data->iso2 = 1;

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso3 required.
     *
     * @return void
     *
     * @test
     */
    public function country_iso3_is_string(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make();
        $data->iso3 = 1;

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country currency required.
     *
     * @return void
     *
     * @test
     */
    public function country_currency_is_string(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

        $this->brand->makeCurrent();

        $data = Country::factory()->make();
        $data->currency = 1;

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Demo Data
     *
     * @return array
     */
    private function demoData(): array
    {
        return [
            'name' => Str::random(5),
            'iso2' => Str::random(5),
            'iso3' => Str::random(5),
            'currency' => 'CUR',
        ];
    }
}
