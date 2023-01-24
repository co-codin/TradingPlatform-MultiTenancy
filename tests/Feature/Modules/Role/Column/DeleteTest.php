<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\Column;

use Modules\Role\Enums\ColumnPermission;
use Modules\Role\Models\Column;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_delete(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::DELETE_COLUMNS));

        $column = Column::factory()->create();
        $response = $this->delete(route('admin.permissions.columns.destroy', ['column' => $column->id]));

        $response->assertNoContent();
        $this->assertModelMissing($column);
    }

    /**
     * @test
     */
    public function user_can_delete_not_found(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::DELETE_COLUMNS));

        $columnId = Column::orderByDesc('id')->first()?->id + 1 ?? 1;
        $response = $this->delete(route('admin.permissions.columns.destroy', ['column' => $columnId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_delete(): void
    {
        $this->authenticateUser();

        $column = Column::factory()->create();
        $response = $this->delete(route('admin.permissions.columns.destroy', ['column' => $column->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $column = Column::factory()->create();
        $response = $this->delete(route('admin.permissions.columns.destroy', ['column' => $column->id]));

        $response->assertUnauthorized();
    }
}
