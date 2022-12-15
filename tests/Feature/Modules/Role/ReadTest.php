<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role;

use Modules\Brand\Models\Brand;
use Modules\Config\Enums\ConfigPermission;
use Modules\Config\Models\Config;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Role;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ReadTest extends BrandTestCase
{
    use TenantAware;
    use HasAuth;

    /**
     * @test
     */
    public function admin_can_get_roles_list(): void
    {
        $this->authenticateAdmin();

        $roles = Role::factory(5)->create();
        $roleIds = $roles->only(['id'])->toArray();

        $response = $this->get(route('admin.roles.index'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);

        $response = $this->get(route('admin.roles.all'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);
    }

    /**
     * @test
     */
    public function authorised_user_can_get_roles_list(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::VIEW_ROLES));

        $roles = Role::factory(5)->create();
        $roleIds = $roles->only(['id'])->toArray();

        $response = $this->get(route('admin.roles.index'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);

        $response = $this->get(route('admin.roles.all'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);
    }

    /**
     * @test
     */
    public function authorised_user_with_brand_can_get_roles_list(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::VIEW_ROLES));

        $roles = Role::factory(5)->create();
        $roleWithBrand = Role::factory(5)->create(['brand_id' => $this->brand->id]);
        $roleIds = $roles->merge($roleWithBrand)->only(['id'])->toArray();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.roles.index'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);

        $response = $this->get(route('admin.roles.all'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);
    }

    /**
     * @test
     */
    public function authorised_user_cant_get_other_brandroles_list(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::VIEW_ROLES));

        $roles = Role::factory(5)->create();
        $roleWithBrand = Role::factory(5)->create(['brand_id' => Brand::factory()->createQuietly()->id]);
        $roleIds = $roles->only(['id'])->toArray();

        $this->brand->makeCurrent();

        $response = $this->get(route('admin.roles.index'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);

        $response->assertJsonMissing([
            'data' => $roleWithBrand->only(['id'])->toArray(),
        ]);

        $response = $this->get(route('admin.roles.all'));

        $response->assertSuccessful();

        $response->assertJson([
            'data' => $roleIds,
        ]);

        $response->assertJsonMissing([
            'data' => $roleWithBrand->only(['id'])->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_get_roles_list(): void
    {
        $response = $this->getJson(route('admin.configs.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function authorized_user_can_get_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::VIEW_ROLES));

        $role = Role::factory()->create();

        $response = $this->getJson(route('admin.roles.show', ['role' => $role->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => $role->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function authorized_user_can_get_brand_role(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::VIEW_ROLES));

        $role = Role::factory()->create(['brand_id' => $this->brand->id]);

        $this->brand->makeCurrent();

        $response = $this->getJson(route('admin.roles.show', ['role' => $role->id]));

        $response->assertOk();

        $response->assertJson([
            'data' => $role->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function authorized_user_cant_get_role_from_other_brand(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::VIEW_ROLES));

        $role = Role::factory()->create(['brand_id' => Brand::factory()->create()->id]);

        $this->brand->makeCurrent();

        $response = $this->getJson(route('admin.roles.show', ['role' => $role->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_get_config(): void
    {
        $role = Role::factory()->create();

        $response = $this->getJson(route('admin.roles.show', ['role' => $role->id]));

        $response->assertUnauthorized();
    }
}
