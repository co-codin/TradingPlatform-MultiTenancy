<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

final class LoginTest extends TestCase
{
    /**
     * @test
     */
    public function login_success(): void
    {
        $response = $this->post(route('admin.auth.login'), [
            'email' => 'test@admin.com',
            'password' => 'admin1',
        ]);

        $response->assertOk();

        $response->assertJsonStructure([
            'user' => [
                'id',
                'first_name',
                'last_name',
                'email',
                'permissions',
                'role',
            ],
            'token',
            'expired_at',
        ]);
    }

    /**
     * @test
     */
    public function login_failed(): void
    {
        $response = $this->post(route('admin.auth.login'), [
            'email' => 'adm@stoxtech.com',
            'password' => 'admin1',
        ]);

        $response->assertUnprocessable();
    }

    protected function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'test@admin.com',
            'password' => Hash::make('admin1'),
        ]);
    }
}
