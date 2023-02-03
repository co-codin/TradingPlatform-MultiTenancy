<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\RoleModel;

use App\Models\Model;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Column;
use Modules\Role\Models\Role;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->artisan('permission:sync');
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::EDIT_ROLES));

        $role = Role::factory()->create();
        $model = Model::has('permissions', '>=', 2)->first();
        $actionNames = $model->permissions()->get(['name'])->map(fn ($p) => head(explode(' ', $p->name)))
            ->unique()->toArray();
        $columnNames = Column::factory(15)->create()->pluck('name')->toArray();
        $selectedActions = Arr::random($actionNames, 2);
        $selectedViewColumns = Arr::random($columnNames, 3);
        $selectedEditColumns = Arr::random($columnNames, 4);
        $response = $this->put(route('admin.roles.models.update', ['id' => $role->id, 'modelId' => $model->id]), [
            'selected_actions' => $selectedActions,
            'selected_view_columns' => $selectedViewColumns,
            'selected_edit_columns' => $selectedEditColumns,
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'permissions_count',
                'available_actions',
                'selected_actions',
                'available_columns',
                'selected_view_columns',
                'selected_edit_columns',
            ],
        ]);

        $response->assertJson([
            'data' => [
                'id' => $model->id,
                'name' => last(explode('\\', $model->name)),
                'permissions_count' => count($actionNames),
                'available_actions' => $actionNames,
                'selected_actions' => $selectedActions,
                'available_columns' => $columnNames,
                'selected_view_columns' => $selectedViewColumns,
                'selected_edit_columns' => $selectedEditColumns,
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::EDIT_ROLES));

        $random = $this->faker->boolean();
        $roleId = $random ? Role::factory()->create()->id : (Role::orderByDesc('id')->first(['id'])?->id + 1);
        $modelId = $random ? (Model::orderByDesc('id')->first(['id'])?->id + 1) : Model::factory()->create()->id;
        $response = $this->put(route('admin.roles.models.update', ['id' => $roleId, 'modelId' => $modelId]), [
            'selected_actions' => [],
            'selected_view_columns' => [],
            'selected_edit_columns' => [],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $role = Role::factory()->create();
        $model = Model::factory()->create();
        $response = $this->put(route('admin.roles.models.update', ['id' => $role->id, 'modelId' => $model->id]), [
            'selected_actions' => [],
            'selected_view_columns' => [],
            'selected_edit_columns' => [],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put(route('admin.roles.models.update', ['id' => 1, 'modelId' => 1]));

        $response->assertUnauthorized();
    }
}
