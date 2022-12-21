<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Preset\Preset;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\User\Models\Preset;
use Modules\User\Models\User;
use Tests\TestCase;

final class ReadTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function admin_can_view(): void
    {
        $this->authenticateAdmin();

        $preset = Preset::factory()->create();

        $response = $this->get(route('admin.users.presets.show', ['worker' => $this->getUser()->id, 'preset' => $preset]));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function can_not_view(): void
    {
        $preset = Preset::factory()->create();

        $this->authenticateUser();

        $response = $this->get(route('admin.users.presets.show', ['worker' => $this->getUser()->id, 'preset' => $preset]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function user_can_view_not_found(): void
    {
        $this->authenticateAdmin();

        $presetId = Preset::query()->orderByDesc('id')->first()?->id + 1 ?? 1;

        $response = $this->get(route('admin.users.presets.show', ['worker' => $this->getUser()->id, 'preset' => $presetId]));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function not_unauthorized_view(): void
    {
        $user = User::factory()->create();
        $preset = Preset::factory()->create();

        $response = $this->get(route('admin.users.presets.show', ['worker' => $user->id, 'preset' => $preset]));

        $response->assertUnauthorized();
    }
}
