<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\Column;

use Modules\Role\Enums\ColumnPermission;
use Modules\Role\Models\Column;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class UpdateTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::EDIT_COLUMNS));

        $column = Column::factory()->create();
        $data = Column::factory()->make();

        $response = $this->put(route('admin.permissions-columns.update', ['permissions_column' => $column->id]),
            $data->toArray());

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);
        $response->assertJson([
            'data' => $data->toArray(),
        ]);
    }

    /**
     * @test
     */
    public function user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::EDIT_COLUMNS));

        $columnId = Column::orderByDesc('id')->first()?->id + 1 ?? 1;
        $data = Column::factory()->make();

        $response = $this->put(route('admin.permissions-columns.update', ['permissions_column' => $columnId]),
            $data->toArray());

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $column = Column::factory()->create();
        $data = Column::factory()->make();

        $response = $this->put(route('admin.permissions-columns.update', ['permissions_column' => $column->id]),
            $data->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unique_name(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::EDIT_COLUMNS));

        $columnOne = Column::factory()->create();
        $columnTwo = Column::factory()->create();
        $response = $this->put(route('admin.permissions-columns.update', ['permissions_column' => $columnOne->id]), [
            'name' => $columnTwo->name,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put('/admin/workers');

        $response->assertStatus(Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
