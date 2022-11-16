<?php

namespace Tests\Feature\Modules\Geo\Country\Admin;

use Illuminate\Contracts\Auth\Authenticatable as UserContract;
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
     * Test authorized user can delete country.
     *
     * @return void
     *
     * @test
     */
    public function authorized_user_can_delete_country(): void
    {
        $this->authenticateWithPermission(CountryPermission::fromValue(CountryPermission::DELETE_COUNTRIES));

        $country = Country::factory()->create();

        $response = $this->deleteJson(route('admin.countries.destroy', ['country' => $country->id]));

        $response->assertNoContent();
    }

    /**
     * Test unauthorized user can`t delete country.
     *
     * @return void
     *
     * @test
     */
    public function unauthorized_user_cant_delete_country(): void
    {
        $country = Country::factory()->create();

        $response = $this->patchJson(route('admin.countries.destroy', ['country' => $country->id]));

        $response->assertUnauthorized();
    }
}
