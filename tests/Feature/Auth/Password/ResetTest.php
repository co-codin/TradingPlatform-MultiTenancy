<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Password;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

final class ResetTest extends TestCase
{
    protected string $testEmail = 'test@admin.com';

    /**
     * @test
     */
    public function accepted(): void
    {
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
        $response = $this->post(route('admin.auth.password.reset'), [
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'token' => Password::createToken($user),
        ]);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertContent(Password::PASSWORD_RESET);
    }

    /**
     * @test
     */
    public function invalid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
        $response = $this->post(route('admin.auth.password.reset'), [
            'email' => $user->email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'token' => Str::random(),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', Password::INVALID_TOKEN);
    }

    /**
     * @test
     */
    public function invalid_user(): void
    {
        $user = User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
        $response = $this->post(route('admin.auth.password.reset'), [
            'email' => 'test@non-existent.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'token' => Password::createToken($user),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonPath('message', Password::INVALID_USER);
    }

    /**
     * @test
     */
    public function unprocessable(): void
    {
        $response = $this->post(route('admin.auth.password.reset'), [
            'email' => 'test',
            'password' => 'password123',
            'password_confirmation' => 'password1234',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['token', 'email', 'password']);
    }
}
