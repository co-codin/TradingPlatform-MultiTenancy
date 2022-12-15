<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role;

use Modules\Brand\Models\Brand;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Role;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function admin_can_delete_role(): void
    {
        $this->authenticateAdmin();

        $role = Role::factory()->create();

        $response = $this->delete(route('admin.roles.destroy', ['role' => $role]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function authorized_user_with_permissions_can_delete_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::DELETE_ROLES));

        $role = Role::factory()->create();

        $response = $this->delete(route('admin.roles.destroy', ['role' => $role]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function authorized_user_with_permissions_can_delete_brand_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::DELETE_ROLES));

        $role = Role::factory()->create(['brand_id' => $this->brand->id]);

        $this->brand->makeCurrent();

        $response = $this->delete(route('admin.roles.destroy', ['role' => $role]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function authorized_user_with_permissions_cant_delete_other_brand_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::DELETE_ROLES));

        $role = Role::factory()->create(['brand_id' => Brand::factory()->createQuietly()->id]);

        $this->brand->makeCurrent();

        $response = $this->delete(route('admin.roles.destroy', ['role' => $role]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function authorized_user_with_permissions_cant_delete_other_brand_rolea(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::DELETE_ROLES));

        $role = Role::factory()->create(['brand_id' => Brand::factory()->createQuietly()->id]);

        $response = $this->delete(route('admin.roles.destroy', ['role' => $role]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_delete_role(): void
    {
        $role = Role::factory()->create();

        $response = $this->delete(route('admin.roles.destroy', ['role' => $role]));

        $response->assertUnauthorized();
    }
}
