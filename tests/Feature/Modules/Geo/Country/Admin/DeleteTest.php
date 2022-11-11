<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Geo\Enums\CountryPermission;
use Modules\Geo\Models\Country;
use Modules\Role\Models\Permission;
use Modules\User\Models\User;
use Tests\TestCase;

class DeleteTest extends TestCase
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
                'name' => CountryPermission::DELETE_COUNTRIES,
            ])?->name);
    }

    /**
     * Test authorized user can delete country.
     *
     * @return void
     */
    public function test_authorized_user_can_delete_country(): void
    {
        $country = Country::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(route('admin.countries.destroy', ['country' => $country->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete country.
     *
     * @return void
     */
    public function test_unauthorized_user_cant_delete_country(): void
    {
        $country = Country::factory()->create();

        $response = $this->patchJson(route('admin.countries.destroy', ['country' => $country->id]));

        $response->assertUnauthorized();
    }
}
