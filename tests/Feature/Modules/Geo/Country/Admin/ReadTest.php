<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test authorized user can get countries list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_countries_list(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::VIEW_COUNTRIES));

        $country = Country::factory()->create();

        $response = $this->getJson(route('admin.countries.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                [
                    'id' => $country->id,
                    'name' => $country->name,
                    'iso2' => $country->iso2,
                    'iso3' => $country->iso3,
                    'currency' => $country->currency,
                ],
            ],
        ]);
    }

    /**
     * Test unauthorized user cant get countries list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_countries_list(): void
    {
        Country::factory()->create();

        $response = $this->getJson(route('admin.countries.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get countries list.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_get_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::VIEW_COUNTRIES));

        $country = Country::factory()->create();

        $response = $this->getJson(route('admin.countries.show', ['country' => $country->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $country->id,
                'name' => $country->name,
                'iso2' => $country->iso2,
                'iso3' => $country->iso3,
                'currency' => $country->currency,
            ],
        ]);
    }

    /**
     * Test unauthorized user cant get countries list.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_get_country(): void
    {
        $country = Country::factory()->create();

        $response = $this->getJson(route('admin.countries.show', ['country' => $country->id]));

        $response->assertUnauthorized();
    }
}
