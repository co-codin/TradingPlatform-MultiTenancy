<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

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

        $country = Country::factory()->create();
        $data = Country::factory()->make();

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'iso2' => $data['iso2'],
                'iso3' => $data['iso3'],
                'currency' => $data['currency'],
            ],
        ]);
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
        $country = Country::factory()->create();
        $data = Country::factory()->make();

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

        $country = Country::factory()->create();
        $targetCountry = Country::factory()->create();
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

        $country = Country::factory()->create();
        $targetCountry = Country::factory()->create();
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

        $country = Country::factory()->create();
        $targetCountry = Country::factory()->create();
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

        $country = Country::factory()->create();
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

        $country = Country::factory()->create();
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

        $country = Country::factory()->create();
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

        $country = Country::factory()->create();
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

        $country = Country::factory()->create();
        $data = Country::factory()->make();
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

        $country = Country::factory()->create();
        $data = Country::factory()->make();
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

        $country = Country::factory()->create();
        $data = Country::factory()->make();
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

        $country = Country::factory()->create();
        $data = Country::factory()->make();
        $data->currency = 1;

        $response = $this->patchJson(route('admin.countries.update', ['country' => $country->id]), $data->toArray());

        $response->assertUnprocessable();
    }
}
