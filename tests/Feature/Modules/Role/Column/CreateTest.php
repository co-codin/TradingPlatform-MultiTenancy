<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\Column;

use Modules\Role\Enums\ColumnPermission;
use Modules\Role\Models\Column;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

final class CreateTest extends TestCase
{
    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::CREATE_COLUMNS));

        $data = Column::factory()->make();

        $response = $this->post(route('admin.permissions-columns.store'), $data->toArray());

        $response->assertCreated();
        $response->assertJson([
            'data' => $data->toArray(),
        ]);
        $this->assertDatabaseHas('columns', $data->toArray());
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $data = Column::factory()->make();

        $response = $this->post(route('admin.permissions-columns.store'), $data->toArray());

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.permissions-columns.store'));

        $response->assertUnauthorized();
    }

    /**
     * @test
     */
    public function not_unique_name(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::CREATE_COLUMNS));

        $column = Column::factory()->create();
        $response = $this->post(route('admin.permissions-columns.store'), [
            'name' => $column->name,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['name']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->make(PermissionRegistrar::class)->registerPermissions();
    }
}
