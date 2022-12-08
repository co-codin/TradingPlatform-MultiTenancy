<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

final class ApiLogoutTest extends TestCase
{
    /**
     * @test
     */
    public function success(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $expiredAt = CarbonImmutable::now()->add(config('auth.api_token_expires_in'));
        $token = $user->createToken('api', expiresAt: $expiredAt);

        $response = $this->withToken($token->plainTextToken)->post(route('admin.token-auth.logout'));
        $response->assertNoContent();

        $this->assertModelMissing($token);
    }

    /**
     * @test
     */
    public function failed(): void
    {
        $response = $this->post(route('admin.token-auth.logout'));

        $response->assertUnauthorized();
        $this->assertGuest();
    }
}
