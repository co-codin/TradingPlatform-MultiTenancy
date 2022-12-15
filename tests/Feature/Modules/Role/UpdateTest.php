<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role;

use Modules\Brand\Models\Brand;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Role;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function admin_can_update_role(): void
    {
        $this->authenticateAdmin();

        $role = Role::factory()->create();
        $data = Role::factory()->make()->toArray();

        $response = $this->patch(route('admin.roles.update', ['role' => $role]), $data);

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $data,
        ]);
    }

    /**
     * @test
     */
    public function authorized_user_can_update_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::EDIT_ROLES));

        $role = Role::factory()->create();
        $data = Role::factory()->make()->toArray();

        $response = $this->patch(route('admin.roles.update', ['role' => $role]), $data);

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $data,
        ]);
    }

    /**
     * @test
     */
    public function authorized_user_can_update_brand_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::EDIT_ROLES));

        $data = Role::factory()->make()->toArray();

        $this->brand->makeCurrent();
        $role = Role::factory()->create();

        $response = $this->patch(route('admin.roles.update', ['role' => $role]), $data);

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $data,
        ]);
    }

    /**
     * @test
     */
    public function authorized_user_cant_update_other_brand_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::EDIT_ROLES));

        $brand = Brand::factory()->createQuietly();
        $data = Role::factory()->make()->toArray();
        $role = Role::factory()->create(['brand_id' => $brand->id]);

        $this->brand->makeCurrent();

        $response = $this->patch(route('admin.roles.update', ['role' => $role]), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_update_role(): void
    {
        $data = Role::factory()->make()->toArray();
        $role = Role::factory()->create();

        $response = $this->patch(route('admin.roles.update', ['role' => $role]), $data);

        $response->assertUnauthorized();
    }
}
