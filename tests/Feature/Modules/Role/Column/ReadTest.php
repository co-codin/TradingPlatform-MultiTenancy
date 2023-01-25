<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\Column;

use Modules\Role\Enums\ColumnPermission;
use Modules\Role\Models\Column;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_view_any(): void
    {
        $columns = Column::factory($count = 5)->create();

        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::VIEW_COLUMNS));
        $response = $this->get(route('admin.permissions.columns.index'));

        $response->assertOk();
        $this->assertCount($count, $response['data']);

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                ],
            ],
        ]);
        $response->assertJson([
            'data' => $columns->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function user_can_view(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::VIEW_COLUMNS));

        $column = Column::factory()->create();
        $response = $this->get(route('admin.permissions.columns.show', ['column' => $column->id]));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);
        $response->assertJson([
            'data' => $column->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function can_view_all(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::VIEW_COLUMNS));

        Column::factory()->count(10)->create();

        $response = $this->get(route('admin.permissions.columns.all'));

        $response->assertOk();

        $response->assertJsonStructure(['data' => [['id', 'name']]]);
    }

    /**
     * @test
     */
    public function can_not_view_all(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.permissions.columns.all'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_view_not_found(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::VIEW_COLUMNS));

        $response = $this->get(route('admin.permissions.columns.show',
            ['column' => Column::orderByDesc('id')->first()?->id + 1 ?? 1]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();
        $response = $this->get(route('admin.permissions.columns.index'));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $column = Column::factory()->create();

        $this->authenticateUser();

        $response = $this->get(route('admin.permissions.columns.show', ['column' => $column->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.permissions.columns.index'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $column = Column::factory()->create();
        $response = $this->get(route('admin.permissions.columns.show', ['column' => $column->id]));

        $response->assertUnauthorized();
    }
}
