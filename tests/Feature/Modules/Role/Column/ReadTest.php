<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\Column;

use Modules\Role\Enums\ColumnPermission;
use Modules\Role\Models\Column;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_view_any(): void
    {
        Column::factory($count = 5)->create();

        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::VIEW_COLUMNS));
        $response = $this->get(route('admin.permissions-columns.index'));

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
    }

    /**
     * @test
     */
    public function user_can_view(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::VIEW_COLUMNS));

        $column = Column::factory()->create();
        $response = $this->get(route('admin.permissions-columns.show', ['permissions_column' => $column->id]));

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
            ],
        ]);
    }

    /**
     * @test
     */
    public function user_can_view_not_found(): void
    {
        $this->authenticateWithPermission(ColumnPermission::fromValue(ColumnPermission::VIEW_COLUMNS));

        $response = $this->get(route('admin.permissions-columns.show',
            ['permissions_column' => Column::orderByDesc('id')->first()?->id + 1 ?? 1]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_view_any(): void
    {
        $this->authenticateUser();
        $response = $this->get(route('admin.permissions-columns.index'));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $column = Column::factory()->create();

        $this->authenticateUser();

        $response = $this->get(route('admin.permissions-columns.show', ['permissions_column' => $column->id]));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function not_unauthorized_view_any(): void
    {
        $response = $this->get(route('admin.permissions-columns.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $column = Column::factory()->create();
        $response = $this->get(route('admin.permissions-columns.show', ['permissions_column' => $column->id]));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }
}
