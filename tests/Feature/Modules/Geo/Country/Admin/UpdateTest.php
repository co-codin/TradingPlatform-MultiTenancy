<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Support\Str;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * Test authorized user can update country.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_update_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData())->toArray();

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data);

        $response->assertOk();

        $response->assertJson(['data' => $data]);
    }

    /**
     * Test unauthorized user can`t update country.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_update_country(): void
    {
        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData());

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $targetCountry = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(['name' => $country->name]);

        $response = $this->patchJson(route('admin.countries.update', ['country' => $targetCountry->id]), $data->toArray());

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $targetCountry = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(['iso2' => $country->iso2]);

        $response = $this->patchJson(route('admin.countries.update', ['country' => $targetCountry->id]), $data->toArray());

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $targetCountry = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(['iso3' => $country->iso3]);

        $response = $this->patchJson(route('admin.countries.update', ['country' => $targetCountry->id]), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country name filled.
     *
     * @return void
     *
     * @test
     */
    public function country_name_is_filled(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(['name' => null])->toArray();

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country iso2 filled.
     *
     * @return void
     *
     * @test
     */
    public function country_iso2_is_filled(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(['iso2' => null])->toArray();

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country iso3 filled.
     *
     * @return void
     *
     * @test
     */
    public function country_iso3_is_filled(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(['iso3' => null])->toArray();

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country currency filled.
     *
     * @return void
     *
     * @test
     */
    public function country_currency_is_filled(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(['currency' => null])->toArray();

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data);

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData());
        $data->name = 1;

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData());
        $data->iso2 = 1;

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData());
        $data->iso3 = 1;

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $this->brand->makeCurrent();

        $country = Country::factory()->create(static::demoData());
        $data = Country::factory()->make(static::demoData());
        $data->currency = 1;

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

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
