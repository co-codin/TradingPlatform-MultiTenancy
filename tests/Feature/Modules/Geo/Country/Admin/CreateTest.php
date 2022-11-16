<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can create country.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_create_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $data = Country::factory()->make();

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'name' => $data['name'],
                'iso2' => $data['iso2'],
                'iso3' => $data['iso3'],
            ],
        ]);
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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $country = Country::factory()->create();

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $country = Country::factory()->create();

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $country = Country::factory()->create();

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::EDIT_COUNTRIES));

        $data = Country::factory()->make();
        $data->iso3 = 1;

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
