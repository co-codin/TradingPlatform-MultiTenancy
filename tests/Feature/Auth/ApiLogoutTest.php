<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

final class ApiLogoutTest extends TestCase
{
    /**
     * @test
     */
    public function logout_success(): void
    {
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
        $response = $this->post(route('admin.token-auth.login'), [
            'email' => $user->email,
            'password' => 'admin1',
        ]);

        $this->assertNotNull($user->tokens()->where('name', 'api')->first());

        $response = $this->withToken($response->json('token'))->post(route('admin.token-auth.logout'));
        $response->assertNoContent();

        $this->assertNull($user->tokens()->where('name', 'api')->first());
    }

    /**
     * @test
     */
    public function logout_failed(): void
    {
        $response = $this->post(route('admin.token-auth.logout'));

        $response->assertUnauthorized();
        $this->assertGuest();
    }
}
