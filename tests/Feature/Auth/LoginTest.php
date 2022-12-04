<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Auth;
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
        $user = $this->getUser();
        $response = $this->post(route('admin.auth.login'), [
            'login' => $user->email,
            'password' => 'admin1',
        ]);

        $response->assertNoContent();
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function login_remember_success(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.auth.login'), [
            'login' => 'test@admin.com',
            'password' => 'admin1',
            'remember_me' => true,
        ]);

        $response->assertNoContent();
        $this->assertAuthenticatedAs($user);

        $response->assertCookie(Auth::guard('web')->getRecallerName(), vsprintf('%s|%s|%s', [
            $user->id,
            $user->getRememberToken(),
            $user->password,
        ]));
    }

    /**
     * @test
     */
    public function login_failed(): void
    {
        $user = $this->getUser();
        $response = $this->post(route('admin.auth.login'), [
            'login' => $user->email,
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
