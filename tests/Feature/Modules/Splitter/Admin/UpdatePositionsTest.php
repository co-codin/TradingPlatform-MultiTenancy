<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Splitter\Admin;

use Modules\Role\Enums\DefaultRole;
use Modules\Role\Models\Role;
use Modules\Splitter\Enums\SplitterPermission;
use Modules\Splitter\Models\Splitter;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class UpdatePositionsTest extends BrandTestCase
{
    use HasAuth;

    /**
     * @test
     */
    public function can_update(): void
    {
        $this->authenticateWithPermission(
            SplitterPermission::fromValue(SplitterPermission::EDIT_SPLITTER_POSITIONS)
        );

        $this->user->assignRole(Role::factory()->create([
            'name' => DefaultRole::AFFILIATE,
            'guard_name' => 'api'
        ]));

        $splitterIds = Splitter::factory()->create(['user_id' => $this->user->id])->pluck('id')->toArray();

        $response = $this->postJson(route('admin.splitter.update-positions'), ['splitterids' => $splitterIds]);

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function can_not_update(): void
    {
        $this->authenticateUser();

        $splitterIds = Splitter::factory()->create(['user_id' => $this->user->id])->pluck('id')->toArray();

        $response = $this->postJson(route('admin.splitter.update-positions'), ['splitterids' => $splitterIds]);

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $splitterIds = Splitter::factory()->create()->pluck('id')->toArray();

        $response = $this->postJson(route('admin.splitter.update-positions'), ['splitterids' => $splitterIds]);

        $response->assertUnauthorized();
    }
}
