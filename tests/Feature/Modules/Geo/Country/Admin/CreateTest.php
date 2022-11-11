<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

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
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'admin@admin.com'
        ])
            ->givePermissionTo(Permission::factory()->create([
                'name' => CountryPermission::CREATE_COUNTRIES,
            ])?->name);
    }

    /**
     * Test authorized user can create country.
     *
     * @return void
     */
    public function test_authorized_user_can_create_country(): void
    {
        $data = Country::factory()->make();

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

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
     */
    public function test_unauthorized_user_cant_create_country(): void
    {
        $data = Country::factory()->make();

        $response = $this->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnauthorized();
    }

    /**
     * Test country name exist.
     *
     * @return void
     */
    public function test_country_name_exist(): void
    {
        $country = Country::factory()->create();

        $data = Country::factory()->make(['name' => $country->name]);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso2 exist.
     *
     * @return void
     */
    public function test_country_iso2_exist(): void
    {
        $country = Country::factory()->create();

        $data = Country::factory()->make(['iso2' => $country->iso2]);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso3 exist.
     *
     * @return void
     */
    public function test_country_iso3_exist(): void
    {
        $country = Country::factory()->create();

        $data = Country::factory()->make(['iso3' => $country->iso3]);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country name required.
     *
     * @return void
     */
    public function test_country_name_is_required(): void
    {
        $data = Country::factory()->make()->toArray();
        unset($data['name']);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country iso2 required.
     *
     * @return void
     */
    public function test_country_iso2_is_required(): void
    {
        $data = Country::factory()->make()->toArray();
        unset($data['iso2']);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country iso3 required.
     *
     * @return void
     */
    public function test_country_iso3_is_required(): void
    {
        $data = Country::factory()->make()->toArray();
        unset($data['iso3']);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data);

        $response->assertUnprocessable();
    }

    /**
     * Test country name is string.
     *
     * @return void
     */
    public function test_country_name_is_string(): void
    {
        $data = Country::factory()->make();
        $data->name = 1;

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso2 is string.
     *
     * @return void
     */
    public function test_country_iso2_is_string(): void
    {
        $data = Country::factory()->make();
        $data->iso2 = 1;

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }

    /**
     * Test country iso3 required.
     *
     * @return void
     */
    public function test_country_iso3_is_string(): void
    {
        $data = Country::factory()->make();
        $data->iso3 = 1;

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertUnprocessable();
    }
}
