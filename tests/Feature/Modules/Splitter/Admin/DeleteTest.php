<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class DeleteTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_delete(): void
    {
        $this->authenticateWithPermission(SplitterPermission::fromValue(SplitterPermission::DELETE_SPLITTER));

        $this->user->assignRole(Role::factory()->create([
            'name' => DefaultRole::AFFILIATE,
            'guard_name' => 'api',
        ]));

        $splitter = Splitter::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson(route('admin.splitter.destroy', ['splitter' => $splitter->id]));

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function can_not_delete(): void
    {
        $splitter = Splitter::factory()->create();

        $response = $this->patchJson(route('admin.splitter.destroy', ['splitter' => $splitter->id]));

        $response->assertUnauthorized();
    }
}
