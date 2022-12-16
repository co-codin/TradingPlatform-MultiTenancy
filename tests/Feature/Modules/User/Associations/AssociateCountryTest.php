<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Associations;

use Modules\Geo\Models\Country;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class AssociateCountryTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->brand->makeCurrent();

        $user = $this->getUser();
        $countries = Country::all();
        $response = $this->put(route('admin.users.country.update', ['id' => $user->id]), [
            'countries' => [
                $countries->random(),
                $countries->random(),
            ],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();

        $this->brand->makeCurrent();

        $response = $this->put(route('admin.users.country.update', ['id' => $user->id]), [
            'countries' => [
                Country::all()->random(),
            ],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->brand->makeCurrent();

        $userId = User::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->put(route('admin.users.country.update', ['id' => $userId]), [
            'countries' => [
                Country::all()->random(),
            ],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $response = $this->put(route('admin.users.country.update', ['id' => 1]));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->makeCurrentTenantAndSetHeader();
    }
}
