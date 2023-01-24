<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::EDIT_SPLITTER)
        );

        $this->user->assignRole(Role::factory()->create([
            'name' => DefaultRole::AFFILIATE,
            'guard_name' => 'api'
        ]));

        $splitter = Splitter::factory()->create();

        $splitterData = Splitter::factory()->make()->toArray();

        $response = $this->patchJson(route('admin.splitter.update', ['splitter' => $splitter->id]), $splitterData);

        $response->assertOk();
        $response->assertJsonStructure([
            'data' => array_keys($splitterData),
        ]);
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $splitter = Splitter::factory()->create();

        $data = Splitter::factory()->make();

        $response = $this->patch(
            route('admin.splitter.update', ['splitter' => $splitter]),
            $data->toArray()
        );

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_found(): void
    {
        $this->authenticateUser();

        $this->user->assignRole(Role::factory()->create([
            'name' => DefaultRole::AFFILIATE,
            'guard_name' => 'api'
        ]));

        $splitterId = Splitter::orderByDesc('id')->first()?->id + 1 ?? 1;

        $data = Splitter::factory()->make();

        $response = $this->patch(
            route('admin.splitter.update', ['splitter' => $splitterId]),
            $data->toArray()
        );

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $splitter = Splitter::factory()->create();

        $response = $this->patch(route('admin.splitter.update', ['splitter' => $splitter]));

        $response->assertUnauthorized();
    }
}
