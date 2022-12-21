<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\User;

use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\UserPermission;
use Modules\User\Models\User;
use Tests\TestCase;

final class ImpersonateTokenTest extends TestCase
{
    private readonly User $targetUser;

    /**
     * @test
     */
    public function can_impersonate(): void
    {
        $this->authenticateWithPermission(UserPermission::fromValue(UserPermission::IMPERSONATE_USER));
        $user = $this->targetUser;
        $response = $this->post(route('admin.users.impersonate.token', ['id' => $user->id]));

        $response->assertOk();

        $response->assertJsonStructure([
            'impersonator',
            'impersonator_token',
            'target_worker',
            'target_token',
            'target_token_expired_at',
        ]);
        $this->assertNotNull($user->tokens()->where('name', 'api')->first());
        $this->actingAs($user)->withToken($response->json('target_token'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function cant_impersonate(): void
    {
        $this->authenticateUser();
        $response = $this->post(route('admin.users.impersonate.token', ['id' => $this->targetUser->id]));

        $response->assertForbidden();
    }

    /**
     * @test
     */
    public function unauthorized(): void
    {
        $response = $this->post(route('admin.users.impersonate.token', ['id' => $this->targetUser->id]));

        $response->assertUnauthorized();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->targetUser = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
    }
}
