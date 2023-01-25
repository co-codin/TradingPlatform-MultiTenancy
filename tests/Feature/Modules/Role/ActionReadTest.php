<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role;

use App\Models\Action;
use Modules\Role\Enums\ActionPermission;
use Tests\TestCase;

final class ActionReadTest extends TestCase
{
    /**
     * @test
     */
    public function can_view_all(): void
    {
        $this->authenticateWithPermission(ActionPermission::fromValue(ActionPermission::VIEW_ACTIONS));

        Action::factory()->count(5)->create();

        $response = $this->get(route('admin.actions.all'));

        $response->assertOk();

        $response->assertJsonStructure(['data' => [['id', 'name']]]);
    }

    /**
     * @test
     */
    public function can_not_view_all(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.actions.all'));

        $response->assertForbidden();
    }
}
