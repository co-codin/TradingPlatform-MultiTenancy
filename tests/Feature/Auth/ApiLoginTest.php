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
    public function success(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.token-auth.login'), [
            'login' => $user->email,
            'password' => 'password',
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
     * @depends success
     */
    public function remember_success(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.token-auth.login'), [
            'login' => $user->email,
            'password' => 'password',
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
    public function failed(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.token-auth.login'), [
            'login' => $user->email,
            'password' => 'random',
        ]);

        $response->assertUnprocessable();
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUser(User::factory()->create([
            'password' => Hash::make('password'),
        ]));
    }
}
