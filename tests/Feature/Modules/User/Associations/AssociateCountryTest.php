<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Associations;

use Modules\Country\Models\Country;
use Modules\Geo\Database\Seeders\CountryTableSeeder;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class AssociateCountryTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $user = User::factory()->create();
        $this->seed(CountryTableSeeder::class);
        $response = $this->put("/admin/users/$user->id/country", [
            'countries' => [
                Country::all()->random(),
                Country::all()->random(),
            ],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->seed(CountryTableSeeder::class);
        $response = $this->put('/admin/users/10/country', [
            'countries' => [
                Country::all()->random(),
            ],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $this->seed(CountryTableSeeder::class);
        $response = $this->put("/admin/users/$user->id/country", [
            'countries' => [
                Country::all()->random(),
            ],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put('/admin/users/1/country');

        $response->assertUnauthorized();
    }
}
