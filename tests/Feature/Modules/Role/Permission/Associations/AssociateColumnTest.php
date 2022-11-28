<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role\Permission\Associations;

use Modules\Role\Database\Seeders\ColumnsTableSeeder;
use Modules\Role\Models\Column;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class AssociateColumnTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_update(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $user = User::factory()->create();
        $this->seed(ColumnsTableSeeder::class);
        $response = $this->put(route('permissions.columns.update', ['id' => $user->id]), [
            'columns' => [
                Column::all()->random(),
                Column::all()->random(),
            ],
        ]);

        $response->assertOk();
    }

    /**
     * @test
     */
    public function user_can_update_not_found(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::EDIT_USERS));

        $this->seed(ColumnsTableSeeder::class);
        $response = $this->put(route('permissions.columns.update', ['id' => 10]), [
            'columns' => [
                Column::all()->random(),
            ],
        ]);

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $user = User::factory()->create();
        $this->seed(ColumnsTableSeeder::class);
        $response = $this->put(route('permissions.columns.update', ['id' => $user->id]), [
            'columns' => [
                Column::all()->random(),
            ],
        ]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->put(route('permissions.columns.update', ['id' => 1]));

        $response->assertUnauthorized();
    }
}
