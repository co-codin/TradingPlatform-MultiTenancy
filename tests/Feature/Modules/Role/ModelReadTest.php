<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Role;

use App\Models\Model;
use Modules\Role\Enums\ModelPermission;
use Tests\TestCase;

final class ModelReadTest extends TestCase
{
    /**
     * @test
     */
    public function can_view_all(): void
    {
        $this->authenticateWithPermission(ModelPermission::fromValue(ModelPermission::VIEW_MODELS));

        Model::factory()->count(10)->create();

        $response = $this->get(route('admin.models.all'));

        $response->assertOk();

        $response->assertJsonStructure(['data' => [['id', 'name']]]);
    }

    /**
     * @test
     */
    public function can_not_view_all(): void
    {
        $this->authenticateUser();

        $response = $this->get(route('admin.models.all'));

        $response->assertForbidden();
    }
}
