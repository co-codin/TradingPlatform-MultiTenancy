<?php

declare(strict_types=1);

namespace Tests\Feature\Auth\Password;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use Modules\User\Models\WorkerInfo;
use Symfony\Component\HttpFoundation\Response;
use Tests\BrandTestCase;
use Tests\Traits\HasAuth;

final class ResetTest extends BrandTestCase
{
    use HasAuth;

    protected string $testEmail = 'test@admin.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUser(User::factory()->create([
            'password' => Hash::make('password'),
        ]));
    }

    /**
     * @test
     */
    public function accepted(): void
    {
        $user = $this->getUser();
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('admin.auth.password.reset'), [
            'email' => $user->getEmail(),
            'password' => self::$basePassword,
            'password_confirmation' => self::$basePassword,
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
        $user = $this->getUser();
        $this->brand->makeCurrent();
        WorkerInfo::factory()->create(['user_id' => $user->id]);
        $response = $this->post(route('admin.auth.password.reset'), [
            'email' => $user->getEmail(),
            'password' => self::$basePassword,
            'password_confirmation' => self::$basePassword,
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
        $user = $this->getUser();
        $this->brand->makeCurrent();
        $response = $this->post(route('admin.auth.password.reset'), [
            'email' => 'test@non-existent.test',
            'password' => self::$basePassword,
            'password_confirmation' => self::$basePassword,
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
            'password' => self::$basePassword,
            'password_confirmation' => self::$basePassword,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['token', 'email', 'password']);
    }
}
