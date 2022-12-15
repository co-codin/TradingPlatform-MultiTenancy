<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role;

use Modules\Brand\Models\Brand;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Role;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function admin_can_create_role(): void
    {
        $this->authenticateAdmin();

        $role = Role::factory()->make();

        $response = $this->post(route('admin.roles.store'), $role->toArray());

        $response->assertCreated();

        $response->assertJson([
            'data' => $role->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function authorized_user_with_permissions_can_create_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::CREATE_ROLES));

        $role = Role::factory()->make();

        $response = $this->post(route('admin.roles.store'), $role->toArray());

        $response->assertCreated();

        $response->assertJson([
            'data' => $role->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function authorized_user_with_permissions_can_create_brand_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::CREATE_ROLES));

        $role = Role::factory()->make();

        $this->brand->makeCurrent();

        $response = $this->post(route('admin.roles.store'), $role->toArray());

        $response->assertCreated();

        $response->assertJson([
            'data' => array_merge($role->toArray(), [
                'brand_id' => $this->brand->id,
            ]),
        ]);
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_create_role(): void
    {
        $role = Role::factory()->make();

        $response = $this->post(route('admin.roles.store'), $role->toArray());

        $response->assertUnauthorized();
    }
}
