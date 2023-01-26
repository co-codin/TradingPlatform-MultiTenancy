<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class CreateTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_create(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::CREATE_SPLITTER)
        );

        $this->user->assignRole(Role::factory()->create([
            'name' => DefaultRole::AFFILIATE,
            'guard_name' => 'api',
        ]));

        $data = Splitter::factory()->make(['user_id' => $this->user->id])->toArray();

        $response = $this->post(route('admin.splitter.store'), $data);

        $response->assertOk();
        $response->assertJson(['data' => $data]);
    }

    /**
     * @test
     */
    public function can_not_create(): void
    {
        $this->authenticateUser();

        $data = Splitter::factory()->make()->toArray();

        $response = $this->post(route('admin.splitter.store'), $data);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function not_unauthorized(): void
    {
        $response = $this->post(route('admin.splitter.store'));

        $response->assertUnauthorized();
    }
}
