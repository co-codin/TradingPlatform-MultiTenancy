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
                'name' => CountryPermission::VIEW_COUNTRIES,
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

        $response->assertOk();

        $response->assertJson([
            'name' => $data['name'],
            'iso2' => $data['iso2'],
            'iso3' => $data['iso3'],
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
     * Test user can`t create country with existed name.
     *
     * @return void
     */
    public function test_user_cant_create_country_with_existed_name(): void
    {
        $country = Country::factory()->create();

        $data = Country::factory()->make()->setAttribute('name', $country->name);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertForbidden();
    }

    /**
     * Test user can`t create country with existed iso2.
     *
     * @return void
     */
    public function test_user_cant_create_country_with_existed_iso2(): void
    {
        $country = Country::factory()->create();

        $data = Country::factory()->make()->setAttribute('iso2', $country->iso2);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertForbidden();
    }

    /**
     * Test user can`t create country with existed iso3.
     *
     * @return void
     */
    public function test_user_cant_create_country_with_existed_iso3(): void
    {
        $country = Country::factory()->create();

        $data = Country::factory()->make(['iso3' => $country->iso3])->setAttribute('iso3', $country->iso3);

        $response = $this->actingAs($this->user)->postJson(route('admin.countries.store'), $data->toArray());

        $response->assertForbidden();
    }
}
