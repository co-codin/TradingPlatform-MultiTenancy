<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

final class ApiLoginTest extends TestCase
{
    /**
     * @test
     */
    public function login_success(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.token-auth.login'), [
            'email' => $user->email,
            'password' => 'admin1',
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'token',
            'expired_at',
        ]);
        $this->assertNotNull($user->tokens()->where('name', 'api')->first());
        $this->actingAs($user)->withToken($response->json('token'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * @depends login_success
     */
    public function login_remember_success(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.token-auth.login'), [
            'email' => $user->email,
            'password' => 'admin1',
            'remember_me' => true,
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'token',
            'expired_at',
        ]);
        $this->actingAs($user)->withToken($response->json('token'));
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function login_failed(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.token-auth.login'), [
            'email' => $user->email,
            'password' => 'random',
        ]);

        $response->assertUnprocessable();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUser(User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]));
    }
}
