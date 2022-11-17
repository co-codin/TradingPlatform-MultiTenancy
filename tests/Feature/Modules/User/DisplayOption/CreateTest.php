<?php

namespace Tests\Feature\Modules\User\DisplayOption;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Modules\User\Enums\UserDisplayOptionPermission;
use Modules\User\Models\DisplayOption;
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
    public function authorized_user_can_create_display_option(): void
    {
        $this->authenticateWithPermission(
            UserDisplayOptionPermission::fromValue(
                UserDisplayOptionPermission::CREATE_USER_DISPLAY_OPTIONS
            )
        );

        $data = DisplayOption::factory()->make(['user_id' => $this->getUser()->id]);

        try {
            $response = $this->post(route('admin.users.display-options.store', ['user' => $this->getUser()]), $data->toArray());
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
dd($response->json('message'));
        $response->assertCreated();

        $response->assertJson([
            'data' => [
                'user_id' => $data['user_id'],
                'name' => $data['name'],
                'columns' => $data['columns'],
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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

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
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::CREATE_COUNTRIES));

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

        $data = Country::factory()->make();
        $data->iso3 = 1;

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
