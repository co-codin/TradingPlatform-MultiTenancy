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
     * @inheritDoc
     */
    public function actingAs(UserContract $user, $guard = null): TestCase
    {
        return parent::actingAs($user, $guard ?: User::DEFAULT_AUTH_GUARD);
    }

    /**
     * Test authorized user can get countries list.
     *
     * @return void
     */
    public function test_authorized_user_can_get_countries_list(): void
    {
        $country = Country::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('admin.countries.index'));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                [
                    'id' => $country->id,
                    'name' => $country->name,
                    'iso2' => $country->iso2,
                    'iso3' => $country->iso3,
                ]
            ]
        ]);
    }

    /**
     * Test unauthorized user cant get countries list.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_get_countries_list(): void
    {
        Country::factory()->create();

        $response = $this->getJson(route('admin.countries.index'));

        $response->assertUnauthorized();
    }

    /**
     * Test authorized user can get countries list.
     *
     * @return void
     */
    public function test_authorized_user_can_get_country(): void
    {
        $country = Country::factory()->create();

        $response = $this->actingAs($this->user)->getJson(route('admin.countries.show', ['country' => $country->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $country->id,
                'name' => $country->name,
                'iso2' => $country->iso2,
                'iso3' => $country->iso3,
            ]
        ]);
    }

    /**
     * Test unauthorized user cant get countries list.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_get_country(): void
    {
        $country = Country::factory()->create();

        $response = $this->getJson(route('admin.countries.show', ['country' => $country->id]));

        $response->assertUnauthorized();
    }
}
