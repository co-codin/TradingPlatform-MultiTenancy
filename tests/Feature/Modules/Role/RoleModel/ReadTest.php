<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\RoleModel;

use App\Models\Action;
use App\Models\Model;
use Illuminate\Support\Arr;
use Modules\Role\Enums\RolePermission;
use Modules\Role\Models\Column;
use Modules\Role\Models\Role;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_view(): void
    {
        Action::factory()->count(5)->create();
        $this->authenticateWithPermission(RolePermission::fromValue(RolePermission::VIEW_ROLES));

        $columns = Column::factory(15)->create();
        $role = Role::factory()->create();
        $model = Model::factory()->create();
        $response = $this->get(route('admin.roles.models.show', ['id' => $role->id, 'modelId' => $model->id]));

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
                'name' => Arr::last(explode('\\', $model->name)),
                'permissions_count' => 0,
                'available_actions' => [],
                'selected_actions' => [],
                'available_columns' => $columns->pluck('name')->toArray(),
                'selected_view_columns' => [],
                'selected_edit_columns' => [],
            ],
        ]);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $this->authenticateUser();

        $role = Role::factory()->create();
        $model = Model::factory()->create();
        $response = $this->get(route('admin.roles.models.show', ['id' => $role->id, 'modelId' => $model->id]));

        $response->assertForbidden();
    }
}
