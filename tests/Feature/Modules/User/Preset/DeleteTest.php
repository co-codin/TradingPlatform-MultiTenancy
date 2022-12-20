<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User\Preset;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Enums\UserPresetPermission;
use Modules\User\Models\Preset;
use Modules\User\Models\User;
use Tests\TestCase;

final class DeleteTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function authorized_user_can_delete(): void
    {
        $this->authenticateWithPermission(
            UserPresetPermission::fromValue(
                UserPresetPermission::DELETE_USER_PRESETS
            )
        );

        $preset = Preset::factory()->create(['user_id' => $this->getUser()->id]);

        $response = $this->delete(
            route('admin.users.presets.destroy', ['worker' => $this->getUser()->id, 'preset' => $preset->id]),
        );

        $response->assertNoContent();
    }

    /**
     * @test
     */
    public function unauthorized_user_cant_delete(): void
    {
        $user = User::factory()->create();

        $preset = Preset::factory()->create(['user_id' => $user->id]);

        $response = $this->delete(
            route('admin.users.presets.destroy', ['worker' => $user->id, 'preset' => $preset->id]),
        );

        $response->assertUnauthorized();
    }
}
